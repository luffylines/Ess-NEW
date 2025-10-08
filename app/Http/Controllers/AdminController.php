<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Total registered users
        $totalUsers = User::count();

        // Active sessions (examples):
        //  - If you're using the "database" session driver and have sessions table:
        try {
            $activeSessions = config('session.driver') === 'database'
                ? DB::table(config('session.table', 'sessions'))->count()
                : Cache::get('active_sessions_count', 0); // fallback cached value
        } catch (\Throwable $e) {
            $activeSessions = Cache::get('active_sessions_count', 0);
        }

        // Page views: placeholder or read from your analytics integration
        $pageViews = Cache::get('page_views_last_7_days', 870);

        // Traffic data for Chart.js (mock/example). Replace this with real GA API data if needed.
        $trafficData = [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'pageViews' => [120, 150, 170, 140, 200, 180, 220],
        ];

        return view('admin.dashboard', compact('totalUsers', 'activeSessions', 'pageViews', 'trafficData'));
    }
}
