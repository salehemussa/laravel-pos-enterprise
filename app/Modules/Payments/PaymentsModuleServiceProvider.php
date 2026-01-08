<?php

namespace App\Modules\Payments;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Modules\Payments\Policies\PaymentPolicy;
use App\Modules\Payments\Integrations\Contracts\PaymentGateway;
use App\Modules\Payments\Integrations\Mpesa\MpesaGateway;
use App\Modules\Payments\Integrations\TigoPesa\TigoPesaGateway;
use App\Modules\Payments\Integrations\AirtelMoney\AirtelMoneyGateway;

class PaymentsModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Default binding (you can swap by config later)
        // We'll resolve gateway by provider name via a small factory (see below)
        $this->app->bind(MpesaGateway::class, MpesaGateway::class);
        $this->app->bind(TigoPesaGateway::class, TigoPesaGateway::class);
        $this->app->bind(AirtelMoneyGateway::class, AirtelMoneyGateway::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        Gate::define('payments.initiate', [PaymentPolicy::class, 'initiate']);
        Gate::define('payments.view', [PaymentPolicy::class, 'view']);
    }
}
