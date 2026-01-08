<?php

namespace App\Modules\Reports\Queries;

use Illuminate\Support\Facades\DB;

class TopProductsQuery
{
    public function run(string $from, string $to, int $limit = 10): array
    {
        $rows = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw('sale_items.product_id')
            ->selectRaw('sale_items.product_name')
            ->selectRaw('sale_items.product_sku')
            ->selectRaw('COALESCE(SUM(sale_items.quantity),0) as qty_sold')
            ->selectRaw('COALESCE(SUM(sale_items.line_total_cents),0) as revenue_cents')
            ->whereBetween('sales.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->where('sales.status', 'completed')
            ->groupBy('sale_items.product_id', 'sale_items.product_name', 'sale_items.product_sku')
            ->orderByDesc('qty_sold')
            ->limit($limit)
            ->get();

        return $rows->map(fn($r) => [
            'product_id' => (int)$r->product_id,
            'product_name' => $r->product_name,
            'product_sku' => $r->product_sku,
            'qty_sold' => (int)$r->qty_sold,
            'revenue_cents' => (int)$r->revenue_cents,
        ])->all();
    }
}
