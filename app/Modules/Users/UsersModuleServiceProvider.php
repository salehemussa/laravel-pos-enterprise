<?php

namespace App\Modules\Users;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User as UserModel;
use App\Modules\Users\Policies\UserPolicy;
use App\Modules\Users\Repositories\Contracts\UserRepository;
use App\Modules\Users\Repositories\Eloquent\EloquentUserRepository;

class UsersModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interface to implementation (DIP) => easier testing & refactor
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        // Modular routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        // Register policy for User model
        Gate::policy(UserModel::class, UserPolicy::class);
    }
}
