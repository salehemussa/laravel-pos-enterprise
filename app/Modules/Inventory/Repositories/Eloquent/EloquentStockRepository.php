<?php

namespace App\Modules\Inventory\Repositories\Eloquent;

use App\Models\InventoryStock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Inventory\Repositories\Contracts\StockRepository;

class EloquentStockRepository implements StockRepository
{
    public function getOrCreateAndLock(int $productId): InventoryStock
    {
        // Create stock row if missing, then lock it (critical for concurrency safety)
        $stock = InventoryStock::query()->firstOrCreate(
            ['product_id' => $productId],
            ['quantity' => 0]
        );

        // Lock row to prevent two concurrent requests overselling stock
        return InventoryStock::query()
            ->whereKey($stock->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return InventoryStock::query()
            ->with(['product:id,name,sku,is_active'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function updateQuantity(InventoryStock $stock, int $newQuantity): InventoryStock
    {
        $stock->quantity = $newQuantity;
        $stock->save();

        return $stock->refresh();
    }
}
