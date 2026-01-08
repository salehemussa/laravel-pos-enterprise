<?php

namespace App\Modules\Notifications\DTOs;

use App\Models\Sale;

final class ReceiptNotificationData
{
    public function __construct(
        public readonly int $saleId,
        public readonly string $saleNo,
        public readonly string $currency,
        public readonly int $totalCents,
        public readonly string $customerName,
        public readonly ?string $customerEmail,
        public readonly ?string $customerPhone,
    ) {}

    /**
     * Build notification data from sale.
     * Keeps jobs simple and stable (no heavy logic inside jobs).
     */
    public static function fromSale(Sale $sale): self
    {
        $customer = $sale->customer; // can be null for walk-in
        $customerName = $customer?->name ?? 'Customer';

        // If you store phone in users table, use $customer->phone
        $phone = $customer->phone ?? null;

        return new self(
            saleId: $sale->id,
            saleNo: $sale->sale_no,
            currency: $sale->currency ?? 'TZS',
            totalCents: (int) $sale->total_cents,
            customerName: $customerName,
            customerEmail: $customer?->email,
            customerPhone: $phone,
        );
    }
}
