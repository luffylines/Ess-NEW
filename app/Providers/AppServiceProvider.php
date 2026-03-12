<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HolidaySyncService;
use Illuminate\Pagination\Paginator;

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

        // Fix SMTP mail scheme at runtime for cloud deployments
        // Port 465 requires 'smtps' scheme, port 587 uses 'smtp' with STARTTLS
        $smtpConfig = config('mail.mailers.smtp');
        if (!empty($smtpConfig) && empty($smtpConfig['scheme'])) {
            $port = (int) ($smtpConfig['port'] ?? 0);
            if ($port === 465) {
                config(['mail.mailers.smtp.scheme' => 'smtps']);
            }
        }
    }
}
