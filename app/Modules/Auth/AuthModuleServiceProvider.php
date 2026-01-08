<?php

namespace App\Modules\Auth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Modules\Auth\Repositories\Contracts\UserRepository;
use App\Modules\Auth\Repositories\Eloquent\EloquentUserRepository;
use App\Modules\Auth\Events\UserRegistered;
use App\Modules\Auth\Listeners\SendWelcomeEmail;

class AuthModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind contract to concrete implementation (Dependency Inversion)
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        // Load module routes (keeps routes modular & clean)
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        // Register event-listener mapping for this module
        Event::listen(UserRegistered::class, SendWelcomeEmail::class);
    }
}
