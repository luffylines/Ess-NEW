<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Get data for admin dashboard
            $data = $this->getAdminDashboardData();
            return view('admin.dashboard', $data);
        } elseif ($user->role === 'hr') {
            // Get data for HR dashboard  
            $data = $this->getHrDashboardData();
            return view('hr.dashboard', $data);
        } else {
            // Get data for employee dashboard
            $data = $this->getEmployeeDashboardData();
            return view('dashboard', $data);
        }
    }

    private function getAdminDashboardData()
    {
        // Total counts
        $totalUsers = User::count();
        $activeSessions = DB::table('sessions')->count();
        $totalAttendances = Attendance::count();
        $totalActivities = ActivityLog::count();

        // Recent activities (last 10)
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Attendance chart data (last 7 days)
        $attendanceChart = $this->getAttendanceChartData();

        // Activity chart data (last 7 days) 
        $activityChart = $this->getActivityChartData();

        return compact(
            'totalUsers',
            'activeSessions', 
            'totalAttendances',
            'totalActivities',
            'recentActivities',
            'attendanceChart',
            'activityChart'
        );
    }

    private function getHrDashboardData()
    {
        // HR specific data
        $totalEmployees = User::where('role', 'employee')->count();
        $pendingLeaves = DB::table('leave_requests')->where('status', 'pending')->count();
        $pendingOvertimes = DB::table('overtime_requests')->where('status', 'pending')->count();
        $todayAttendances = Attendance::whereDate('created_at', Carbon::today())->count();

        return compact(
            'totalEmployees',
            'pendingLeaves',
            'pendingOvertimes', 
            'todayAttendances'
        );
    }

    private function getEmployeeDashboardData()
    {
        $user = Auth::user();
        
        // Employee specific data
        $myAttendances = Attendance::where('user_id', $user->id)->count();
        $myLeaves = DB::table('leave_requests')->where('user_id', $user->id)->count();
        $myOvertimes = DB::table('overtime_requests')->where('user_id', $user->id)->count();
        $lastAttendance = Attendance::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return compact(
            'myAttendances',
            'myLeaves', 
            'myOvertimes',
            'lastAttendance'
        );
    }

    private function getAttendanceChartData()
    {
        $labels = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');
            $totals[] = Attendance::whereDate('created_at', $date)->count();
        }

        return compact('labels', 'totals');
    }

    private function getActivityChartData()
    {
        $labels = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');
            $totals[] = ActivityLog::whereDate('created_at', $date)->count();
        }

        return compact('labels', 'totals');
    }
}
