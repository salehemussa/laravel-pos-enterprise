<?php

namespace App\Shared;

use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Helpers & value objects are stateless, no bindings needed now
    }

    public function boot(): void
    {
        // Place for macros if needed later
    }
}
