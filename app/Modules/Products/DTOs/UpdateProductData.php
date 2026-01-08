<?php

namespace App\Modules\Products\DTOs;

use App\Modules\Products\Http\Requests\UpdateProductRequest;

final class UpdateProductData
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $sku = null,
        public readonly ?string $description = null,
        public readonly ?float $price = null,
        public readonly ?float $cost = null,
        public readonly ?bool $is_active = null,
    ) {}

    public static function fromRequest(UpdateProductRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'] ?? null,
            sku: $data['sku'] ?? null,
            description: array_key_exists('description', $data) ? $data['description'] : null,
            price: array_key_exists('price', $data) ? (float) $data['price'] : null,
            cost: array_key_exists('cost', $data) ? (float) $data['cost'] : null,
            is_active: array_key_exists('is_active', $data) ? (bool)$data['is_active'] : null,
        );
    }
}
