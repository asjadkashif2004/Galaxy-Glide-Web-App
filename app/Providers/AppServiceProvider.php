<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS when using ngrok (optional)
        if (str_contains(config('app.url'), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}
