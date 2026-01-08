<?php

namespace App\Modules\Reports\Http\Resources;

class TopProductResource
{
    public static function collection(array $rows): array
    {
        return array_map(fn($r) => [
            'product_id' => $r['product_id'],
            'product_name' => $r['product_name'],
            'product_sku' => $r['product_sku'],
            'qty_sold' => $r['qty_sold'],
            'revenue_cents' => $r['revenue_cents'],
            'revenue' => $r['revenue_cents'] / 100,
        ], $rows);
    }
}
