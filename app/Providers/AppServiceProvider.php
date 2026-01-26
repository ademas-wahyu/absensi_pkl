<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Illuminate\Support\Facades\Request::header('x-forwarded-proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Dynamic APP_URL for Ngrok/Localhost to prevent redirect to port 80
        if (! app()->runningInConsole()) {
            $host = \Illuminate\Support\Facades\Request::getHttpHost();
            if ($host) {
                \Illuminate\Support\Facades\URL::forceRootUrl(\Illuminate\Support\Facades\Request::getScheme().'://'.$host);
            }
        }
    }
}
