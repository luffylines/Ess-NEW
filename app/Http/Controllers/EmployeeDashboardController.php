<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\ActivityLog;
use App\Models\Holiday;
use App\Helpers\HolidayHelper;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthStart = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
        $monthEnd = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth()->endOfDay();

        // Calculate working days in the month (excluding weekends and holidays)
        $totalWorkingDays = HolidayHelper::getMonthlyWorkingDays($currentYear, $currentMonth);
        
        // Get remaining working days in current month from today
        $remainingWorkingDays = HolidayHelper::getRemainingWorkingDaysThisMonth();

        // Get holidays for the month (for display and calculations)
        $holidays = Holiday::getHolidaysForMonth($currentYear, $currentMonth);
        $holidayCount = $holidays->count();

        // Check if today is a holiday
        $isTodayHoliday = Holiday::isHoliday(Carbon::now());

        // Attendance count in current month (reset monthly)
        $attendanceCount = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
            ->count();

        // Attendance percentage based on working days (excluding holidays/weekends)
        $attendancePercentage = $totalWorkingDays > 0 ? round(($attendanceCount / $totalWorkingDays) * 100, 1) : 0;

        // Total overtime hours in last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();
        $today = Carbon::now()->endOfDay();

        $totalOvertime = OvertimeRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereBetween('created_at', [$thirtyDaysAgo, $today])
            ->sum('total_hours');
        // Total leave taken in current year
        $totalLeaveTaken = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('start_date', $currentYear)
            ->get()
            ->sum(function($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });


            

        // Calculate leave balance (assuming 21 days annual leave)
        $annualLeaveEntitlement = 21;
        $yearStart = Carbon::create($currentYear, 1, 1);
        $totalLeaveTakenThisYear = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereBetween('start_date', [$yearStart, $today])
            ->get()
            ->sum(function($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });

        $leaveBalance = max(0, $annualLeaveEntitlement - $totalLeaveTakenThisYear);

        // Upcoming leave
        $upcomingLeave = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '>=', Carbon::today())
            ->orderBy('start_date', 'asc')
            ->first();

        // Recent activities
        $recentActivities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pending requests counts
        $pendingLeaveRequests = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $pendingOvertimeRequests = OvertimeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Recent attendance records (last 7 days)
        $recentAttendance = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [Carbon::now()->subDays(7)->format('Y-m-d'), $today->format('Y-m-d')])
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        // Chart data for last 30 days attendance
        $chartData = $this->getAttendanceChartData($user->id);

        // Monthly attendance summary with holiday awareness
        $monthlyStats = $this->getMonthlyAttendanceStats($user->id, $currentYear);

        // Get upcoming holidays (next 30 days)
        $upcomingHolidays = HolidayHelper::getHolidaysInRange(
            Carbon::now(), 
            Carbon::now()->addDays(30)
        )->take(5);

        return view('dashboard', compact(
            'user',
            'attendanceCount',
            'totalWorkingDays',
            'remainingWorkingDays',      // new: remaining working days this month
            'holidayCount',              // holidays in current month
            'holidays',                  // holiday collection for current month
            'isTodayHoliday',           // new: is today a holiday
            'upcomingHolidays',         // new: upcoming holidays
            'attendancePercentage',
            'totalOvertime',
            'totalLeaveTaken',
            'leaveBalance',
            'upcomingLeave',
            'recentActivities',
            'pendingLeaveRequests',
            'pendingOvertimeRequests',
            'recentAttendance',
            'chartData',
            'monthlyStats'
        ));
    }

    /**
     * Calculate total days between start and end date inclusive
     */
    private function calculateTotalDays($startDate, $endDate)
    {
        return $startDate->diffInDays($endDate) + 1;
    }

    /**
     * Return list of holidays for given year and month (hardcoded example)
     * You can replace this with DB call or config file as needed
     */
    private function getHolidays($year, $month)
    {
        $holidays = [
            // Add your company holidays here
            '2025-10-15',
            '2025-10-31',
            // Add more dates as needed...
        ];

        return collect($holidays)->filter(function ($date) use ($year, $month) {
            $carbonDate = Carbon::parse($date);
            return $carbonDate->year == $year && $carbonDate->month == $month;
        })->values()->all();
    }

    private function getAttendanceChartData($userId)
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            $hasAttendance = Attendance::where('user_id', $userId)
                ->where('date', $date->format('Y-m-d'))
                ->exists();

            $data[] = $hasAttendance ? 1 : 0;
        }

        return compact('labels', 'data');
    }

    private function getMonthlyAttendanceStats($userId, $year)
    {
        $stats = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($year, $month, 1)->startOfDay();
            $monthEnd = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

            $attendanceCount = Attendance::where('user_id', $userId)
                ->whereBetween('date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
                ->count();

            // Use holiday-aware working days calculation
            $workingDays = HolidayHelper::getMonthlyWorkingDays($year, $month);

            $stats[] = [
                'month' => $monthStart->format('M'),
                'attendance' => $attendanceCount,
                'working_days' => $workingDays,
                'percentage' => $workingDays > 0 ? round(($attendanceCount / $workingDays) * 100, 1) : 0
            ];
        }

        return $stats;
    }
}
