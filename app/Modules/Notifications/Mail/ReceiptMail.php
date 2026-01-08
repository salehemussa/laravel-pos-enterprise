<?php

namespace App\Modules\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Modules\Notifications\DTOs\ReceiptNotificationData;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ReceiptNotificationData $data) {}

    public function build(): self
    {
        return $this
            ->subject("Receipt: {$this->data->saleNo}")
            // You can use markdown mail template
            ->markdown('emails.receipt', [
                'data' => $this->data,
            ]);
    }
}
