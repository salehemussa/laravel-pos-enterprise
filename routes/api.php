<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    require app_path('Modules/Auth/Routes/api.php');
    require app_path('Modules/Users/Routes/api.php');
    require app_path('Modules/Products/Routes/api.php');
    require app_path('Modules/Inventory/Routes/api.php');
    require app_path('Modules/Payments/Routes/api.php');
    require app_path('Modules/Reports/Routes/api.php');
    // Notifications usually has no routes (events/listeners/jobs), so no need here
});
