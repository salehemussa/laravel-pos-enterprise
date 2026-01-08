<?php

namespace App\Modules\Reports\Http\Resources;

class InventoryValuationResource
{
    public static function make(array $payload): array
    {
        return [
            'totals' => [
                'total_cost_value_cents' => $payload['totals']['total_cost_value_cents'],
                'total_sell_value_cents' => $payload['totals']['total_sell_value_cents'],
                'total_cost_value' => $payload['totals']['total_cost_value_cents'] / 100,
                'total_sell_value' => $payload['totals']['total_sell_value_cents'] / 100,
            ],
            'items' => array_map(fn($i) => [
                'product_id' => $i['product_id'],
                'product_name' => $i['product_name'],
                'product_sku' => $i['product_sku'],
                'quantity' => $i['quantity'],
                'stock_cost_value_cents' => $i['stock_cost_value_cents'],
                'stock_sell_value_cents' => $i['stock_sell_value_cents'],
                'stock_cost_value' => $i['stock_cost_value_cents'] / 100,
                'stock_sell_value' => $i['stock_sell_value_cents'] / 100,
            ], $payload['items']),
        ];
    }
}
