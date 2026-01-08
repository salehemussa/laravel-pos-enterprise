<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Inventory\Http\Controllers\InventoryController;

Route::middleware('auth:api')->prefix('inventory')->group(function () {

    Route::get('stocks', [InventoryController::class, 'stocks']);
    Route::get('movements', [InventoryController::class, 'movements']);

    Route::post('add', [InventoryController::class, 'add']);
    Route::post('remove', [InventoryController::class, 'remove']);
    Route::post('adjust', [InventoryController::class, 'adjust']);
});
