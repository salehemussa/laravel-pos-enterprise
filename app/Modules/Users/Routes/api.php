<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Products\Http\Controllers\ProductController;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('products', ProductController::class);
});
