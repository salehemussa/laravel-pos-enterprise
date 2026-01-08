<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Payments\Http\Controllers\PaymentController;
use App\Modules\Payments\Http\Controllers\WebhookController;

Route::prefix('api')->group(function () {
    // Internal (staff)
    Route::middleware(['auth:api'])->group(function () {
        Route::post('payments/initiate', [PaymentController::class, 'initiate']);
        Route::get('payments/intents/{reference}', [PaymentController::class, 'showIntent']);
    });

    // Public callback endpoint (provider hits this)
    Route::post('payments/webhooks/{provider}', [WebhookController::class, 'handle']);
});
