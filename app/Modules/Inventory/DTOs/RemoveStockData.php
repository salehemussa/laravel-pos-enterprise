<?php

namespace App\Modules\Inventory\DTOs;

use App\Modules\Inventory\Http\Requests\RemoveStockRequest;

final class RemoveStockData
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly ?string $reason,
        public readonly ?string $referenceType,
        public readonly ?int $referenceId,
    ) {}

    public static function fromRequest(RemoveStockRequest $request): self
    {
        $data = $request->validated();

        return new self(
            productId: (int)$data['product_id'],
            quantity: (int)$data['quantity'],
            reason: $data['reason'] ?? null,
            referenceType: $data['reference_type'] ?? null,
            referenceId: isset($data['reference_id']) ? (int)$data['reference_id'] : null,
        );
    }
}
