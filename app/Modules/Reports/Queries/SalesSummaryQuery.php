<?php

namespace App\Modules\Reports\Queries;

use Illuminate\Support\Facades\DB;

class SalesSummaryQuery
{
    public function run(string $from, string $to, ?int $soldBy, ?string $paymentStatus, ?string $status): array
    {
        $q = DB::table('sales')
            ->selectRaw('COUNT(*) as sales_count')
            ->selectRaw('COALESCE(SUM(subtotal_cents),0) as subtotal_cents')
            ->selectRaw('COALESCE(SUM(discount_cents),0) as discount_cents')
            ->selectRaw('COALESCE(SUM(tax_cents),0) as tax_cents')
            ->selectRaw('COALESCE(SUM(total_cents),0) as total_cents')
            ->selectRaw('COALESCE(SUM(paid_cents),0) as paid_cents')
            ->selectRaw('COALESCE(SUM(balance_cents),0) as balance_cents')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);

        if ($soldBy) $q->where('sold_by', $soldBy);
        if ($paymentStatus) $q->where('payment_status', $paymentStatus);
        if ($status) $q->where('status', $status);

        $row = (array) $q->first();

        // Normalize for safety
        return [
            'sales_count' => (int) ($row['sales_count'] ?? 0),
            'subtotal_cents' => (int) ($row['subtotal_cents'] ?? 0),
            'discount_cents' => (int) ($row['discount_cents'] ?? 0),
            'tax_cents' => (int) ($row['tax_cents'] ?? 0),
            'total_cents' => (int) ($row['total_cents'] ?? 0),
            'paid_cents' => (int) ($row['paid_cents'] ?? 0),
            'balance_cents' => (int) ($row['balance_cents'] ?? 0),
        ];
    }
}
