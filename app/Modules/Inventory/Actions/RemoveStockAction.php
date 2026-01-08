<?php

namespace App\Modules\Inventory\Actions;

use Illuminate\Support\Facades\DB;
use App\Modules\Inventory\DTOs\AddStockData;
use App\Modules\Inventory\Repositories\Contracts\StockRepository;
use App\Modules\Inventory\Repositories\Contracts\StockMovementRepository;

class AddStockAction
{
    public function __construct(
        private readonly StockRepository $stocks,
        private readonly StockMovementRepository $movements,
    ) {}

    public function execute(AddStockData $data, int $performedBy): array
    {
        return DB::transaction(function () use ($data, $performedBy) {
            // Lock current stock row
            $stock = $this->stocks->getOrCreateAndLock($data->productId);

            $newQty = $stock->quantity + $data->quantity;

            // Update snapshot table
            $stock = $this->stocks->updateQuantity($stock, $newQty);

            // Write ledger entry
            $movement = $this->movements->create([
                'product_id' => $data->productId,
                'type' => 'IN',
                'quantity' => $data->quantity,
                'reason' => $data->reason ?? 'Stock added',
                'performed_by' => $performedBy,
            ]);

            return ['stock' => $stock, 'movement' => $movement];
        });
    }
}
