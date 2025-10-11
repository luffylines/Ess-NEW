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
        } elseif ($user->role === 'hr' || $user->role === 'manager') {
            // Get data for HR dashboard (both HR and Manager use same dashboard)
            $data = $this->managementDashboard();
            return view('hr.management-dashboard', $data);
        } else {
            // Redirect to EmployeeDashboardController for employees
            $employeeDashboard = new \App\Http\Controllers\EmployeeDashboardController();
            return $employeeDashboard->index();
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

     public function managementDashboard()
    {
        $currentUser = Auth::user();
        
        if (!in_array($currentUser->role, ['hr', 'manager'])) {
            abort(403, 'Only HR and Managers can access attendance management.');
        }

        $today = Carbon::today();
        
        // Get all employees
        $employees = User::where('role', 'employee')->orderBy('name')->get();

        // Get today's attendance records
        $todayAttendances = Attendance::with('user')
            ->where('date', $today)
            ->get()
            ->keyBy('user_id');

        // Find employees who missed attendance today
        $missedAttendanceEmployees = $employees->filter(function($employee) use ($todayAttendances) {
            return !isset($todayAttendances[$employee->id]);
        });

        // Get recent attendance statistics
        $stats = [
            'total_employees' => $employees->count(),
            'present_today' => $todayAttendances->count(),
            'missed_today' => $missedAttendanceEmployees->count(),
            'pending_approvals' => Attendance::where('status', 'pending')->count(),
        ];

        return compact(
            'employees', 
            'todayAttendances', 
            'missedAttendanceEmployees', 
            'stats',
            'today'
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
