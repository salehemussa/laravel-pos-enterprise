<?php

namespace App\Modules\Payments\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Sale;
use App\Modules\Payments\Models\PaymentIntent;
use App\Modules\Payments\DTOs\InitiatePaymentData;
use App\Modules\Payments\Integrations\Contracts\PaymentGateway;
use App\Modules\Payments\Integrations\Mpesa\MpesaGateway;
use App\Modules\Payments\Integrations\TigoPesa\TigoPesaGateway;
use App\Modules\Payments\Integrations\AirtelMoney\AirtelMoneyGateway;

class InitiatePaymentAction
{
    public function execute(InitiatePaymentData $data, int $initiatedBy): PaymentIntent
    {
        return DB::transaction(function () use ($data, $initiatedBy) {
            $sale = Sale::query()->lockForUpdate()->findOrFail($data->saleId);

            // Prevent paying for voided/refunded sales
            if (in_array($sale->status, ['voided', 'refunded'], true)) {
                throw ValidationException::withMessages([
                    'sale_id' => 'This sale cannot be paid (voided/refunded).',
                ]);
            }

            $amountCents = $this->toCents($data->amount);

            // Optional: ensure you don't pay more than balance
            $balance = max(0, (int)$sale->balance_cents);
            if ($balance > 0 && $amountCents > $balance) {
                throw ValidationException::withMessages([
                    'amount' => 'Amount exceeds current sale balance.',
                ]);
            }

            $intent = PaymentIntent::query()->create([
                'sale_id' => $sale->id,
                'provider' => $data->provider,
                'amount_cents' => $amountCents,
                'status' => 'processing',
                'reference' => $this->makeReference(),
                'initiated_by' => $initiatedBy,
                'meta' => array_filter([
                    'phone' => $data->phone,
                ]),
            ]);

            // Cash/card can be "succeeded immediately" (POS manual confirmation)
            if (in_array($data->provider, ['cash', 'card'], true)) {
                $intent->update([
                    'status' => 'succeeded',
                    'provider_reference' => strtoupper($data->provider) . '-MANUAL-' . $intent->reference,
                ]);

                // Apply to sale
                app(ApplyPaymentToSaleAction::class)->execute($sale, $intent->amount_cents);

                return $intent->refresh();
            }

            // For mobile money: call gateway
            $gateway = $this->resolveGateway($data->provider);
            $response = $gateway->initiate($intent);

            $intent->update([
                'provider_reference' => $response['provider_reference'] ?? null,
                'meta' => array_merge($intent->meta ?? [], $response['meta'] ?? []),
            ]);

            return $intent->refresh();
        });
    }

    private function resolveGateway(string $provider): PaymentGateway
    {
        return match ($provider) {
            'mpesa' => app(MpesaGateway::class),
            'tigopesa' => app(TigoPesaGateway::class),
            'airtelmoney' => app(AirtelMoneyGateway::class),
            default => throw ValidationException::withMessages(['provider' => 'Unsupported provider.']),
        };
    }

    private function makeReference(): string
    {
        // Unique, short, shareable
        return 'PI-' . Str::upper(Str::random(18));
    }

    private function toCents(float $amount): int
    {
        return (int) round($amount * 100);
    }
}
