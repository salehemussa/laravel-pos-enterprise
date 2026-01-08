<?php

namespace App\Modules\Payments\Integrations\Mpesa;

use App\Modules\Payments\Integrations\Contracts\PaymentGateway;
use App\Modules\Payments\Models\PaymentIntent;

class MpesaGateway implements PaymentGateway
{
    public function initiate(PaymentIntent $intent): array
    {
        // TODO: Call real M-Pesa API (STK Push / C2B etc.)
        // Return provider reference you get from API.
        return [
            'provider_reference' => 'MPESA-DEMO-' . $intent->reference,
            'meta' => ['channel' => 'stk_push'],
        ];
    }

    public function parseWebhook(array $payload): array
    {
        // TODO: Map actual M-Pesa callback fields
        // Ensure event_id is unique per callback
        $eventId = $payload['event_id'] ?? sha1(json_encode($payload));

        return [
            'event_id' => $eventId,
            'status' => $payload['status'] ?? 'pending',
            'amount_cents' => (int) ($payload['amount_cents'] ?? 0),
            'provider_txn_id' => $payload['provider_txn_id'] ?? null,
        ];
    }

    public function verifyWebhook(array $headers, array $payload): bool
    {
        // TODO: Verify signature/token if provider supports it
        return true;
    }
}
