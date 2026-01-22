<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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

        });


             RateLimiter::for('api', function (Request $request) {
               // Default API limiter: 60/min per user (if logged in), else per IP
              return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
                  });

             RateLimiter::for('login', function (Request $request) {
                 // Stricter: 10/min per IP + email (helps against brute force)
                 $key = strtolower($request->input('email', 'guest')).'|'.$request->ip();
             return Limit::perMinute(10)->by($key);
                 });
    } 
}
