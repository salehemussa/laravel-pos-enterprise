<?php

namespace App\Modules\Notifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Notifications\DTOs\ReceiptNotificationData;
use App\Modules\Notifications\Actions\SendReceiptEmailAction;

class SendReceiptEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly ReceiptNotificationData $data) {}

    public function handle(SendReceiptEmailAction $action): void
    {
        $action->execute($this->data);
    }
}
