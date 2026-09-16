<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\OvertimeRequest;
use App\Models\ActivityLog;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Manila');
        $today = $now->copy()->startOfDay();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        $yearStart = Carbon::create($currentYear, 1, 1, 0, 0, 0, 'Asia/Manila');
        $yearEnd = $yearStart->copy()->endOfYear();
        $monthStart = Carbon::create($currentYear, $currentMonth, 1, 0, 0, 0, 'Asia/Manila');
        $monthEnd = $monthStart->copy()->endOfMonth();
        $thirtyDaysAgo = $today->copy()->subDays(30);
        $sevenDaysAgo = $today->copy()->subDays(7);
        $upcomingHolidayEnd = $today->copy()->addDays(30);
        $holidayQueryEnd = $upcomingHolidayEnd->gt($yearEnd) ? $upcomingHolidayEnd : $yearEnd;

        /*
         * Performance note:
         * The old dashboard executed dozens of sequential queries (30 daily attendance
         * exists() checks + 12 monthly attendance queries + 12 holiday queries, plus
         * separate leave/overtime queries). With a remote database this made navigation
         * take many seconds. Load each dataset once and calculate the dashboard in memory.
         */
        $holidayRecords = Holiday::query()
            ->where('is_active', true)
            ->where('country', 'PH')
            ->whereBetween('date', [$yearStart->toDateString(), $holidayQueryEnd->toDateString()])
            ->orderBy('date')
            ->get(['id', 'date', 'name', 'type', 'country', 'region', 'is_active']);

        $holidayDates = $holidayRecords
            ->map(fn ($holiday) => Carbon::parse($holiday->date)->format('Y-m-d'))
            ->flip();

        $holidays = $holidayRecords
            ->filter(fn ($holiday) => Carbon::parse($holiday->date)->year === $currentYear
                && Carbon::parse($holiday->date)->month === $currentMonth)
            ->values();
        $holidayCount = $holidays->count();
        $isTodayHoliday = $holidayDates->has($today->format('Y-m-d'));
        $upcomingHolidays = $holidayRecords
            ->filter(function ($holiday) use ($today, $upcomingHolidayEnd) {
                $date = Carbon::parse($holiday->date)->startOfDay();
                return $date->betweenIncluded($today, $upcomingHolidayEnd);
            })
            ->take(5)
            ->values();

        $totalWorkingDays = $this->countWorkingDays($monthStart, $monthEnd, $holidayDates);
        $remainingWorkingDays = $today->lt($monthEnd)
            ? $this->countWorkingDays($today->copy()->addDay(), $monthEnd, $holidayDates)
            : 0;

        $attendanceRangeStart = $thirtyDaysAgo->lt($yearStart) ? $thirtyDaysAgo : $yearStart;
        $attendanceRecords = Attendance::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$attendanceRangeStart->toDateString(), $today->toDateString()])
            ->orderBy('date', 'desc')
            ->get();

        $attendanceCount = $attendanceRecords
            ->filter(fn ($attendance) => Carbon::parse($attendance->date)->year === $currentYear
                && Carbon::parse($attendance->date)->month === $currentMonth)
            ->count();

        $attendancePercentage = $totalWorkingDays > 0
            ? round(($attendanceCount / $totalWorkingDays) * 100, 1)
            : 0;

        $recentAttendance = $attendanceRecords
            ->filter(fn ($attendance) => Carbon::parse($attendance->date)->startOfDay()->gte($sevenDaysAgo))
            ->take(7)
            ->values();

        $attendanceDateSet = $attendanceRecords
            ->map(fn ($attendance) => Carbon::parse($attendance->date)->format('Y-m-d'))
            ->flip();

        $chartData = $this->buildAttendanceChartData($today, $attendanceDateSet);
        $monthlyStats = $this->buildMonthlyAttendanceStats(
            $currentYear,
            $attendanceRecords,
            $holidayDates
        );

        $leaveRequests = LeaveRequest::query()
            ->where('user_id', $user->id)
            ->get(['id', 'leave_type', 'start_date', 'end_date', 'status']);

        $approvedLeaves = $leaveRequests->where('status', 'approved');
        $totalLeaveTaken = $approvedLeaves
            ->filter(function ($leave) use ($thirtyDaysAgo, $today) {
                $start = Carbon::parse($leave->start_date)->startOfDay();
                $end = Carbon::parse($leave->end_date)->startOfDay();
                return $start->lte($today) && $end->gte($thirtyDaysAgo);
            })
            ->sum(fn ($leave) => Carbon::parse($leave->start_date)
                ->diffInDays(Carbon::parse($leave->end_date)) + 1);

        $totalLeaveTakenAllTime = $approvedLeaves
            ->sum(fn ($leave) => Carbon::parse($leave->start_date)
                ->diffInDays(Carbon::parse($leave->end_date)) + 1);

        $leaveBalance = max(0, 15 - $totalLeaveTakenAllTime);
        $upcomingLeave = $approvedLeaves
            ->filter(fn ($leave) => Carbon::parse($leave->start_date)->startOfDay()->gte($today))
            ->sortBy('start_date')
            ->first();
        $pendingLeaveRequests = $leaveRequests->where('status', 'pending')->count();

        $overtimeRequests = OvertimeRequest::query()
            ->where('user_id', $user->id)
            ->get(['id', 'overtime_date', 'total_hours', 'status']);

        $totalOvertime = $overtimeRequests
            ->filter(function ($overtime) use ($thirtyDaysAgo, $today) {
                if ($overtime->status !== 'approved') {
                    return false;
                }
                $date = Carbon::parse($overtime->overtime_date)->startOfDay();
                return $date->betweenIncluded($thirtyDaysAgo, $today);
            })
            ->sum(fn ($overtime) => (float) ($overtime->total_hours ?? 0));
        $pendingOvertimeRequests = $overtimeRequests->where('status', 'pending')->count();

        $recentActivities = ActivityLog::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'attendanceCount',
            'totalWorkingDays',
            'remainingWorkingDays',
            'holidayCount',
            'holidays',
            'isTodayHoliday',
            'upcomingHolidays',
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

    private function countWorkingDays(Carbon $startDate, Carbon $endDate, Collection $holidayDates): int
    {
        $cursor = $startDate->copy()->startOfDay();
        $end = $endDate->copy()->startOfDay();
        $workingDays = 0;

        while ($cursor->lte($end)) {
            if (!$cursor->isWeekend() && !$holidayDates->has($cursor->format('Y-m-d'))) {
                $workingDays++;
            }
            $cursor->addDay();
        }

        return $workingDays;
    }

    private function buildAttendanceChartData(Carbon $today, Collection $attendanceDateSet): array
    {
        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = $attendanceDateSet->has($date->format('Y-m-d')) ? 1 : 0;
        }

        return compact('labels', 'data');
    }

    private function buildMonthlyAttendanceStats(
        int $year,
        Collection $attendanceRecords,
        Collection $holidayDates
    ): array {
        $attendanceByMonth = array_fill(1, 12, 0);

        foreach ($attendanceRecords as $attendance) {
            $date = Carbon::parse($attendance->date);
            if ($date->year === $year) {
                $attendanceByMonth[$date->month]++;
            }
        }

        $stats = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Manila');
            $monthEnd = $monthStart->copy()->endOfMonth();
            $workingDays = $this->countWorkingDays($monthStart, $monthEnd, $holidayDates);
            $attendance = $attendanceByMonth[$month];

            $stats[] = [
                'month' => $monthStart->format('M'),
                'attendance' => $attendance,
                'working_days' => $workingDays,
                'percentage' => $workingDays > 0 ? round(($attendance / $workingDays) * 100, 1) : 0,
            ];
        }

        return $stats;
    }
}
