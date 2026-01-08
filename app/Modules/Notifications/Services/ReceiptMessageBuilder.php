<?php

namespace App\Modules\Notifications\Services;

use App\Modules\Notifications\DTOs\ReceiptNotificationData;

class ReceiptMessageBuilder
{
    public function buildSms(ReceiptNotificationData $data): string
    {
        // Keep SMS short (160 chars target)
        $amount = number_format($data->totalCents / 100, 2);

        return "Receipt {$data->saleNo}. Paid: {$amount} {$data->currency}. Thank you - " . config('app.name');
    }
}
