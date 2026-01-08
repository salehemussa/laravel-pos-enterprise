<?php

namespace App\Modules\Notifications;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Modules\Notifications\Events\SalePaid;
use App\Modules\Notifications\Listeners\DispatchReceiptNotifications;
use App\Modules\Notifications\Sms\Contracts\SmsGateway;
use App\Modules\Notifications\Sms\Beem\BeemSmsGateway;

class NotificationsModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind SMS contract to implementation (easy to swap provider later)
        $this->app->bind(SmsGateway::class, BeemSmsGateway::class);
    }

    public function boot(): void
    {
        // When a sale is paid, dispatch email + sms jobs
        Event::listen(SalePaid::class, DispatchReceiptNotifications::class);
    }
}
