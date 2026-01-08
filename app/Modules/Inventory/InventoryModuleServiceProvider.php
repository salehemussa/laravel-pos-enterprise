<?php

namespace App\Modules\Inventory;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Modules\Inventory\Policies\InventoryPolicy;
use App\Modules\Inventory\Repositories\Contracts\StockRepository;
use App\Modules\Inventory\Repositories\Contracts\StockMovementRepository;
use App\Modules\Inventory\Repositories\Eloquent\EloquentStockRepository;
use App\Modules\Inventory\Repositories\Eloquent\EloquentStockMovementRepository;

class InventoryModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StockRepository::class, EloquentStockRepository::class);
        $this->app->bind(StockMovementRepository::class, EloquentStockMovementRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        // Policy is not tied to a single model here (it's module-level rules),
        // so we define Gate abilities directly.
        Gate::define('inventory.view', [InventoryPolicy::class, 'view']);
        Gate::define('inventory.manage', [InventoryPolicy::class, 'manage']);
    }
}
