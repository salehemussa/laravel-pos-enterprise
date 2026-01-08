<?php

namespace App\Modules\Inventory\Actions;

use Illuminate\Support\Facades\DB;
use App\Modules\Inventory\DTOs\AdjustStockData;
use App\Modules\Inventory\Repositories\Contracts\StockRepository;
use App\Modules\Inventory\Repositories\Contracts\StockMovementRepository;

class AdjustStockAction
{
    public function __construct(
        private readonly StockRepository $stocks,
        private readonly StockMovementRepository $movements,
    ) {}

    public function execute(AdjustStockData $data, int $performedBy): array
    {
        return DB::transaction(function () use ($data, $performedBy) {
            $stock = $this->stocks->getOrCreateAndLock($data->productId);

            $oldQty = $stock->quantity;
            $newQty = $data->newQuantity;

            // Record the difference as movement quantity (positive)
            $diff = abs($newQty - $oldQty);

            // If no difference, we can still return without writing movement
            if ($diff === 0) {
                return ['stock' => $stock, 'movement' => null];
            }

            $stock = $this->stocks->updateQuantity($stock, $newQty);

            $movement = $this->movements->create([
                'product_id' => $data->productId,
                'type' => 'ADJUST',
                'quantity' => $diff,
                'reason' => $data->reason ?? "Stock adjusted from {$oldQty} to {$newQty}",
                'performed_by' => $performedBy,
            ]);

            return ['stock' => $stock, 'movement' => $movement];
        });
    }
}
