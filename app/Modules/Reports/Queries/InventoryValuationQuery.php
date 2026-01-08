<?php

namespace App\Modules\Reports\Queries;

use Illuminate\Support\Facades\DB;

class InventoryValuationQuery
{
    public function run(): array
    {
        // Join products + inventory_stocks to compute value
        $rows = DB::table('inventory_stocks')
            ->join('products', 'products.id', '=', 'inventory_stocks.product_id')
            ->selectRaw('products.id as product_id')
            ->selectRaw('products.name as product_name')
            ->selectRaw('products.sku as product_sku')
            ->selectRaw('inventory_stocks.quantity')
            ->selectRaw('products.cost_cents')
            ->selectRaw('products.price_cents')
            ->get();

        $items = $rows->map(fn($r) => [
            'product_id' => (int)$r->product_id,
            'product_name' => $r->product_name,
            'product_sku' => $r->product_sku,
            'quantity' => (int)$r->quantity,
            'cost_cents' => (int)($r->cost_cents ?? 0),
            'price_cents' => (int)($r->price_cents ?? 0),
            'stock_cost_value_cents' => (int)$r->quantity * (int)($r->cost_cents ?? 0),
            'stock_sell_value_cents' => (int)$r->quantity * (int)($r->price_cents ?? 0),
        ])->all();

        $totals = [
            'total_cost_value_cents' => array_sum(array_column($items, 'stock_cost_value_cents')),
            'total_sell_value_cents' => array_sum(array_column($items, 'stock_sell_value_cents')),
        ];

        return ['items' => $items, 'totals' => $totals];
    }
}
