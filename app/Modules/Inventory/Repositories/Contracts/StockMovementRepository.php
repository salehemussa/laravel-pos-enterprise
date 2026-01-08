<?php

namespace App\Modules\Inventory\Repositories\Contracts;

use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StockMovementRepository
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;

    public function create(array $attributes): StockMovement;
}
