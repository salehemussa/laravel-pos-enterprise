<?php

namespace App\Modules\Products\Actions;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Modules\Products\DTOs\UpdateProductData;
use App\Modules\Products\Repositories\Contracts\ProductRepository;

class UpdateProductAction
{
    public function __construct(private readonly ProductRepository $products) {}

    public function execute(Product $product, UpdateProductData $data): Product
    {
        return DB::transaction(function () use ($product, $data) {

            // Validate unique SKU while ignoring current product
            if ($data->sku !== null) {
                Validator::make(
                    ['sku' => $data->sku],
                    ['sku' => ['string', 'max:60', Rule::unique('products', 'sku')->ignore($product->id)]]
                )->validate();
            }

            $attributes = array_filter([
                'name' => $data->name,
                'description' => $data->description,
                'is_active' => $data->is_active,
            ], fn($v) => $v !== null);

            if ($data->sku !== null) {
                $attributes['sku'] = strtoupper($data->sku);
            }

            if ($data->price !== null) {
                $attributes['price_cents'] = $this->toCents($data->price);
            }

            if ($data->cost !== null) {
                $attributes['cost_cents'] = $this->toCents($data->cost);
            }

            return $this->products->update($product, $attributes);
        });
    }

    private function toCents(float $amount): int
    {
        return (int) round($amount * 100);
    }
}
