<?php

namespace App\Modules\Products;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product as ProductModel;
use App\Modules\Products\Policies\ProductPolicy;
use App\Modules\Products\Repositories\Contracts\ProductRepository;
use App\Modules\Products\Repositories\Eloquent\EloquentProductRepository;

class ProductsModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind contract => implementation (clean dependency management)
        $this->app->bind(ProductRepository::class, EloquentProductRepository::class);
    }

    public function boot(): void
    {
        // Load module API routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        // Register policy for Product model
        Gate::policy(ProductModel::class, ProductPolicy::class);
    }
}
