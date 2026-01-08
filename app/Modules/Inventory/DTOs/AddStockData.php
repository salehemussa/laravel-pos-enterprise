<?php

namespace App\Modules\Inventory\DTOs;

use App\Modules\Inventory\Http\Requests\AddStockRequest;

final class AddStockData
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly ?string $reason,
    ) {}

    public static function fromRequest(AddStockRequest $request): self
    {
        $data = $request->validated();

        return new self(
            productId: (int)$data['product_id'],
            quantity: (int)$data['quantity'],
            reason: $data['reason'] ?? null,
        );
    }
}
