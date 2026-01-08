<?php

namespace App\Modules\Notifications\Actions;

use Illuminate\Support\Facades\Mail;
use App\Modules\Notifications\DTOs\ReceiptNotificationData;
use App\Modules\Notifications\Mail\ReceiptMail;

class SendReceiptEmailAction
{
    public function execute(ReceiptNotificationData $data): void
    {
        // Mail facade handles transport config (SMTP, Mailgun, SES, etc)
        Mail::to($data->customerEmail)->send(new ReceiptMail($data));
    }
}
