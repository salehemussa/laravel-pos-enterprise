<?php

namespace App\Modules\Products\Actions;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Modules\Products\Repositories\Contracts\ProductRepository;

class DeleteProductAction
{
    public function __construct(private readonly ProductRepository $products) {}

    public function execute(Product $product): void
    {
        DB::transaction(function () use ($product) {
            // For POS, you might prefer soft disable:
            // $this->products->update($product, ['is_active' => false]);
            // return;

            $this->products->delete($product);
        });
    }
}
