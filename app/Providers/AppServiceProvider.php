<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    // If you use policies, you can map them here:
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function boot(): void
    {
        // DO NOT call $this->registerPolicies() on Laravel 10/11+.
        // Just define your gates here.
        Gate::define('admin', fn ($user) => $user->role === 'admin');
    }
}