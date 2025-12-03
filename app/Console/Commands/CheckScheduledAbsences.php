<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkSchedule;
use App\Models\Attendance;
use Carbon\Carbon;

class CheckScheduledAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:check-absences {--date=} {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for employees who have schedules but no attendance records and mark them as absent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();
        $dryRun = $this->option('dry-run');

        $this->info("Checking scheduled absences for date: " . $date->format('Y-m-d'));
        
        if ($dryRun) {
            $this->warn("DRY RUN MODE - No changes will be made");
        }

        // Get all schedules for the specified date
        $schedules = WorkSchedule::with(['employee', 'store'])
            ->whereDate('schedule_date', $date)
            ->where('status', '!=', 'missed') // Don't process already marked as missed
            ->get();

        $this->info("Found " . $schedules->count() . " schedules for this date");

        $absentCount = 0;
        $presentCount = 0;

        foreach ($schedules as $schedule) {
            // Check if employee has any attendance record for this date
            $hasAttendance = Attendance::where('user_id', $schedule->employee_id)
                ->whereDate('created_at', $date)
                ->exists();

            if ($hasAttendance) {
                $presentCount++;
                $this->line("✓ {$schedule->employee->name} - Present");
                
                // Update schedule status to completed if they attended
                if (!$dryRun && $schedule->status !== 'completed') {
                    $schedule->update(['status' => 'completed']);
                }
            } else {
                $absentCount++;
                $this->error("✗ {$schedule->employee->name} - ABSENT (Schedule: {$schedule->shift_start} - {$schedule->shift_end})");
                
                // Mark schedule as missed
                if (!$dryRun) {
                    $schedule->update(['status' => 'missed']);
                    
                    // Optionally create an absence record in attendance table
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

        $this->newLine();
        $this->info("Summary:");
        $this->info("Present employees: " . $presentCount);
        $this->error("Absent employees: " . $absentCount);
        
        if (!$dryRun && $absentCount > 0) {
            $this->warn("Marked {$absentCount} scheduled employees as absent");
        }

        return 0;
    }
}
