<?php

namespace App\Modules\Notifications\Listeners;

use App\Modules\Notifications\Events\SalePaid;
use App\Modules\Notifications\DTOs\ReceiptNotificationData;
use App\Modules\Notifications\Jobs\SendReceiptEmailJob;
use App\Modules\Notifications\Jobs\SendReceiptSmsJob;

class DispatchReceiptNotifications
{
    public function handle(SalePaid $event): void
    {
        // Load customer relationship once (avoid lazy load in queue)
        $sale = $event->sale->loadMissing(['customer', 'items']);

        $data = ReceiptNotificationData::fromSale($sale);

        // Dispatch notifications asynchronously (fast response for payment webhook)
        if ($data->customerEmail) {
            SendReceiptEmailJob::dispatch($data);
        }

        if ($data->customerPhone) {
            SendReceiptSmsJob::dispatch($data);
        }
    }
}
