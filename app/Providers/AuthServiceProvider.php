<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Model::class => Policy::class,
    ];

    public function boot(): void
    {
        Gate::define('admin', fn ($user) => $user->role === 'admin');
    }
}
