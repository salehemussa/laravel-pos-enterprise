<?php

namespace App\Modules\Products\DTOs;

use App\Modules\Products\Http\Requests\StoreProductRequest;

final class CreateProductData
{
    public function __construct(
        public readonly string $name,
        public readonly string $sku,
        public readonly ?string $description,
        public readonly float $price, // in major units (e.g. TZS)
        public readonly ?float $cost,
        public readonly bool $is_active,
    ) {}

    public static function fromRequest(StoreProductRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'],
            sku: $data['sku'],
            description: $data['description'] ?? null,
            price: (float) $data['price'],
            cost: array_key_exists('cost', $data) ? (float) $data['cost'] : null,
            is_active: (bool)($data['is_active'] ?? true),
        );
    }
}
