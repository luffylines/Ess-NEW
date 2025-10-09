<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Summary counts
        $totalUsers = User::count();
        $activeSessions = DB::table('sessions')->count();
        $totalAttendances = DB::table('attendances')->count();
        $totalActivities = DB::table('activity_logs')->count();

        // Recent activity logs (last 5)
        $recentActivities = DB::table('activity_logs')
            ->select('id', 'user_id', 'action', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Attendance chart (last 7 days)
        $attendanceData = DB::table('attendances')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $attendanceChart = [
            'labels' => $attendanceData->pluck('date')->map(fn($d) => date('D', strtotime($d)))->toArray(),
            'totals' => $attendanceData->pluck('total')->toArray(),
        ];

        // Activity chart (last 7 days)
        $activityData = DB::table('activity_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $activityChart = [
            'labels' => $activityData->pluck('date')->map(fn($d) => date('D', strtotime($d)))->toArray(),
            'totals' => $activityData->pluck('total')->toArray(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeSessions',
            'totalAttendances',
            'totalActivities',
            'recentActivities',
            'attendanceChart',
            'activityChart'
        ));
    }
}
