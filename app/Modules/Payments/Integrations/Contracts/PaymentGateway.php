<?php

namespace App\Modules\Payments\Integrations\Contracts;

use App\Modules\Payments\Models\PaymentIntent;

interface PaymentGateway
{
    /**
     * Initiate a payment request with the provider.
     * Return provider_reference (e.g., checkoutRequestId) and optional meta.
     */
    public function initiate(PaymentIntent $intent): array;

    /**
     * Parse webhook payload and normalize it to a standard array.
     * Must return:
     * - event_id (unique)
     * - status: succeeded|failed|pending
     * - amount_cents
     * - provider_txn_id (optional)
     */
    public function parseWebhook(array $payload): array;

    /**
     * Verify webhook authenticity (signature, IP whitelist, etc).
     */
    public function verifyWebhook(array $headers, array $payload): bool;
}
