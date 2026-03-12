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
        // Port 465 requires 'smtps', port 587 auto-negotiates STARTTLS (scheme=null)
        $smtpConfig = config('mail.mailers.smtp');
        if (!empty($smtpConfig)) {
            $port = (int) ($smtpConfig['port'] ?? 0);
            $currentScheme = $smtpConfig['scheme'] ?? null;
            if ($port === 465 && $currentScheme !== 'smtps') {
                config(['mail.mailers.smtp.scheme' => 'smtps']);
            } elseif ($port === 587 && $currentScheme !== null && $currentScheme !== 'smtps') {
                // Port 587 uses STARTTLS which is auto-negotiated when scheme is null
                config(['mail.mailers.smtp.scheme' => null]);
            }
        }
    }
}
