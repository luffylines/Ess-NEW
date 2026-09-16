<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HolidaySyncService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use App\Models\Attendance;
use Carbon\Carbon;

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

        /*
         * Lightweight state endpoint for the employee dashboard.
         * The dashboard previously fetched the entire /attendance/my page just to
         * discover today's workday state. On the single-worker Render service that
         * heavy request could block a sidebar navigation click for several seconds.
         */
        Route::middleware(['web', 'auth'])
            ->get('/api/attendance/today-state', function () {
                $user = request()->user();
                $today = Carbon::now('Asia/Manila')->format('Y-m-d');

                $attendance = Attendance::query()
                    ->where('user_id', $user->id)
                    ->where('date', $today)
                    ->first(['time_in', 'time_out', 'breaktime_in', 'breaktime_out']);

                $state = 'not_started';
                if ($attendance?->time_in) {
                    if ($attendance->time_out) {
                        $state = 'complete';
                    } elseif ($attendance->breaktime_in && !$attendance->breaktime_out) {
                        $state = 'on_break';
                    } elseif ($attendance->breaktime_out) {
                        $state = 'working_resumed';
                    } else {
                        $state = 'working';
                    }
                }

                return response()->json([
                    'state' => $state,
                    'server_time' => Carbon::now('Asia/Manila')->toIso8601String(),
                ])->header('Cache-Control', 'no-store, private');
            })
            ->name('attendance.today-state');
    }
}
