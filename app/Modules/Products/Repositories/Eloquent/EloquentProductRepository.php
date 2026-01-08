<?php

namespace App\Modules\Products\Repositories\Eloquent;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Products\Repositories\Contracts\ProductRepository;

class EloquentProductRepository implements ProductRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        // Centralize ordering & filtering logic
        return Product::query()
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(array $attributes): Product
    {
        return Product::query()->create($attributes);
    }

    public function update(Product $product, array $attributes): Product
    {
        $product->fill($attributes);
        $product->save();

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
