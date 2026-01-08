<?php

namespace App\Modules\Inventory\DTOs;

use App\Modules\Inventory\Http\Requests\AdjustStockRequest;

final class AdjustStockData
{
    public function __construct(
        public readonly int $productId,
        public readonly int $newQuantity,
        public readonly ?string $reason,
    ) {}

    public static function fromRequest(AdjustStockRequest $request): self
    {
        $data = $request->validated();

        return new self(
            productId: (int)$data['product_id'],
            newQuantity: (int)$data['new_quantity'],
            reason: $data['reason'] ?? null,
        );
    }
}
