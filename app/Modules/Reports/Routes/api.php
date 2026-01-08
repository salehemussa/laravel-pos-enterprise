<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Reports\Http\Controllers\ReportController;

Route::prefix('api/reports')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('sales/summary', [ReportController::class, 'salesSummary']);
        Route::get('sales/daily', [ReportController::class, 'dailySales']);
        Route::get('products/top', [ReportController::class, 'topProducts']);
        Route::get('inventory/valuation', [ReportController::class, 'inventoryValuation']);
    });
