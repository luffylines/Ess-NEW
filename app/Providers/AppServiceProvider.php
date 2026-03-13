<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HolidaySyncService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HolidaySyncService::class, function ($app) {
            return new HolidaySyncService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        
        // Force HTTPS URLs when accessed via HTTPS
        if (request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https') {
            \URL::forceScheme('https');
        }

        // ...existing code...
    }
}
