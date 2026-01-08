<?php

namespace App\Modules\Payments\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Modules\Payments\Models\PaymentIntent;
use App\Modules\Payments\Models\PaymentTransaction;
use App\Modules\Payments\Integrations\Contracts\PaymentGateway;
use App\Models\Sale;

class HandleWebhookAction
{
    public function execute(string $provider, array $headers, array $payload, PaymentGateway $gateway): array
    {
        // Verify authenticity
        if (!$gateway->verifyWebhook($headers, $payload)) {
            throw ValidationException::withMessages(['webhook' => 'Invalid webhook signature.']);
        }

        $parsed = $gateway->parseWebhook($payload);

        return DB::transaction(function () use ($provider, $payload, $parsed) {

            // Find intent by provider_reference or internal reference in payload (depends provider)
            $providerRef = $payload['provider_reference'] ?? null;
            $intentRef = $payload['reference'] ?? null;

            $intent = PaymentIntent::query()
                ->when($providerRef, fn($q) => $q->where('provider_reference', $providerRef))
                ->when(!$providerRef && $intentRef, fn($q) => $q->where('reference', $intentRef))
                ->lockForUpdate()
                ->first();

            if (!$intent) {
                throw ValidationException::withMessages(['intent' => 'Payment intent not found.']);
            }

            // Idempotency: if we already stored this event_id, ignore duplicates
            $exists = PaymentTransaction::query()->where('event_id', $parsed['event_id'])->exists();
            if ($exists) {
                return ['ok' => true, 'duplicate' => true];
            }

            $txn = PaymentTransaction::query()->create([
                'payment_intent_id' => $intent->id,
                'provider' => $provider,
                'status' => $parsed['status'],
                'provider_txn_id' => $parsed['provider_txn_id'] ?? null,
                'event_id' => $parsed['event_id'],
                'amount_cents' => (int) ($parsed['amount_cents'] ?? 0),
                'payload' => $payload,
            ]);

            // Update intent status
            if ($parsed['status'] === 'succeeded') {
                $intent->update(['status' => 'succeeded']);
            } elseif ($parsed['status'] === 'failed') {
                $intent->update(['status' => 'failed']);
            } else {
                $intent->update(['status' => 'processing']);
            }

            // Apply money to sale on success
            if ($parsed['status'] === 'succeeded') {
                $sale = Sale::query()->lockForUpdate()->findOrFail($intent->sale_id);
                app(ApplyPaymentToSaleAction::class)->execute($sale, $txn->amount_cents);
            }

            return ['ok' => true, 'duplicate' => false];
        });
    }

    
}
