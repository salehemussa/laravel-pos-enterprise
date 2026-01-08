<?php

namespace App\Modules\Reports;

use Illuminate\Support\ServiceProvider;

class ReportsModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Queries are resolved via container automatically
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
    }
}
