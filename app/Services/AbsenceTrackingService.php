<?php

namespace App\Services;

use App\Models\WorkSchedule;
use App\Models\Attendance;
use Carbon\Carbon;

class AbsenceTrackingService
{
    /**
     * Check and mark employees as absent for a specific date
     * 
     * @param Carbon $date
     * @param bool $dryRun
     * @return array
     */
    public function checkScheduledAbsences(Carbon $date, bool $dryRun = false): array
    {
        // Get all schedules for the specified date
        $schedules = WorkSchedule::with(['employee', 'store'])
            ->whereDate('schedule_date', $date)
            ->where('status', '!=', 'missed') // Don't process already marked as missed
            ->get();

        $absentCount = 0;
        $presentCount = 0;
        $results = [];

        foreach ($schedules as $schedule) {
            // Check if employee has any attendance record for this date
            $hasAttendance = Attendance::where('user_id', $schedule->employee_id)
                ->whereDate('created_at', $date)
                ->exists();

            if ($hasAttendance) {
                $presentCount++;
                $results[] = [
                    'employee' => $schedule->employee->name,
                    'status' => 'present',
                    'schedule' => $schedule->shift_start . ' - ' . $schedule->shift_end
                ];
                
                // Update schedule status to completed if they attended
                if (!$dryRun && $schedule->status !== 'completed') {
                    $schedule->update(['status' => 'completed']);
                }
            } else {
                $absentCount++;
                $results[] = [
                    'employee' => $schedule->employee->name,
                    'status' => 'absent',
                    'schedule' => $schedule->shift_start . ' - ' . $schedule->shift_end,
                    'store' => $schedule->store ? $schedule->store->name : 'Unknown'
                ];
                
                // Mark schedule as missed and create absence record
                if (!$dryRun) {
                    $schedule->update(['status' => 'missed']);
                    
                    // Create an absence record in attendance table
                    Attendance::create([
                        'user_id' => $schedule->employee_id,
                        'time_in' => null,
                        'time_out' => null,
                        'break_in' => null,
                        'break_out' => null,
                        'work_hours' => 0,
                        'status' => 'absent',
                        'location' => $schedule->store ? $schedule->store->name : 'Unknown',
                        'created_at' => $date->startOfDay(),
                        'updated_at' => $date->startOfDay(),
                    ]);
                }
            }
        }

        return [
            'date' => $date->format('Y-m-d'),
            'total_schedules' => $schedules->count(),
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
            'results' => $results,
            'dry_run' => $dryRun
        ];
    }

    /**
     * Get absence statistics for a date range
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getAbsenceStatistics(Carbon $startDate, Carbon $endDate): array
    {
        $missedSchedules = WorkSchedule::with(['employee', 'store'])
            ->where('status', 'missed')
            ->whereBetween('schedule_date', [$startDate, $endDate])
            ->orderBy('schedule_date', 'desc')
            ->get();

        $statistics = [
            'total_missed' => $missedSchedules->count(),
            'employees_with_absences' => $missedSchedules->groupBy('employee_id')->count(),
            'absences_by_date' => [],
            'absences_by_employee' => []
        ];

        // Group by date
        $statistics['absences_by_date'] = $missedSchedules->groupBy(function ($schedule) {
            return $schedule->schedule_date->format('Y-m-d');
        })->map(function ($group) {
            return $group->count();
        });

        // Group by employee
        $statistics['absences_by_employee'] = $missedSchedules->groupBy('employee.name')->map(function ($group) {
            return [
                'count' => $group->count(),
                'employee' => $group->first()->employee->name,
                'employee_id' => $group->first()->employee->employee_id
            ];
        });

        return $statistics;
    }
}