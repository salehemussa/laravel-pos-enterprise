<?php

namespace App\Modules\Inventory\Repositories\Eloquent;

use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Inventory\Repositories\Contracts\StockMovementRepository;

class EloquentStockMovementRepository implements StockMovementRepository
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return StockMovement::query()
            ->with(['product:id,name,sku', 'performer:id,name,email'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $attributes): StockMovement
    {
        return StockMovement::query()->create($attributes);
    }
}
