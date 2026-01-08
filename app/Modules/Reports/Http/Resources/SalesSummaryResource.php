<?php

namespace App\Modules\Reports\Http\Resources;

class SalesSummaryResource
{
    public static function make(array $data, string $currency = 'TZS'): array
    {
        // Provide both cents + readable amounts
        return [
            'sales_count' => $data['sales_count'],
            'currency' => $currency,
            'subtotal_cents' => $data['subtotal_cents'],
            'discount_cents' => $data['discount_cents'],
            'tax_cents' => $data['tax_cents'],
            'total_cents' => $data['total_cents'],
            'paid_cents' => $data['paid_cents'],
            'balance_cents' => $data['balance_cents'],

            'subtotal' => $data['subtotal_cents'] / 100,
            'discount' => $data['discount_cents'] / 100,
            'tax' => $data['tax_cents'] / 100,
            'total' => $data['total_cents'] / 100,
            'paid' => $data['paid_cents'] / 100,
            'balance' => $data['balance_cents'] / 100,
        ];
    }
}
