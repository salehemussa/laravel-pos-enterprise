<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {

            // API routes (JWT)
            Route::prefix('api/v1')
                ->middleware('api')
                ->group(function () {

                    // 🔐 Auth
                    require app_path('Modules/Auth/Routes/api.php');

                    // 👤 Users
                    require app_path('Modules/Users/Routes/api.php');

                    // 📦 Products
                    require app_path('Modules/Products/Routes/api.php');

                    // 🏬 Inventory
                    require app_path('Modules/Inventory/Routes/api.php');

                    // 🧾 Sales
                    require app_path('Modules/Sales/Routes/api.php');

                    // 💳 Payments
                    require app_path('Modules/Payments/Routes/api.php');

                    // 📊 Reports
                    require app_path('Modules/Reports/Routes/api.php');
                });

            // Web routes (if needed later)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
