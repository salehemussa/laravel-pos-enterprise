<?php

namespace App\Modules\Products\Actions;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Modules\Products\DTOs\CreateProductData;
use App\Modules\Products\Repositories\Contracts\ProductRepository;

class CreateProductAction
{
    public function __construct(private readonly ProductRepository $products) {}

    public function execute(CreateProductData $data, int $createdByUserId): Product
    {
        return DB::transaction(function () use ($data, $createdByUserId) {

            // Business rule example: SKU should not contain spaces (optional)
            if (preg_match('/\s/', $data->sku)) {
                throw ValidationException::withMessages([
                    'sku' => 'SKU must not contain spaces.',
                ]);
            }

            return $this->products->create([
                'name' => $data->name,
                'sku' => strtoupper($data->sku), // normalize
                'description' => $data->description,

                // Convert to cents
                'price_cents' => $this->toCents($data->price),
                'cost_cents' => $data->cost !== null ? $this->toCents($data->cost) : null,

                'is_active' => $data->is_active,
                'created_by' => $createdByUserId,
            ]);
        });
    }

    private function toCents(float $amount): int
    {
        // Example: 2500.00 => 250000 cents (if you treat "cents" as 1/100)
        // If your currency has no cents (TZS), you can store as "amount" integer directly.
        // But keeping cents is good if later you add USD payments.
        return (int) round($amount * 100);
    }
}
