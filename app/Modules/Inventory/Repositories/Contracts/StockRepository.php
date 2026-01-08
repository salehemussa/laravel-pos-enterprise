<?php

namespace App\Modules\Inventory\Repositories\Contracts;

use App\Models\InventoryStock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StockRepository
{
    /**
     * Locks the stock row FOR UPDATE to avoid race conditions during sales.
     * If row doesn't exist yet, you should create it first (see implementation).
     */
    public function getOrCreateAndLock(int $productId): InventoryStock;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function updateQuantity(InventoryStock $stock, int $newQuantity): InventoryStock;
}
