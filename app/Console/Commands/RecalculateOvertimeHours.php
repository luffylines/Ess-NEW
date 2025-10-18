<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OvertimeRequest;
use Carbon\Carbon;

class RecalculateOvertimeHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overtime:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate total hours for all overtime requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recalculating overtime hours...');
        
        $overtimeRequests = OvertimeRequest::all();
        $updated = 0;
        
        foreach ($overtimeRequests as $overtime) {
            if ($overtime->start_time && $overtime->end_time) {
                $startTime = Carbon::createFromFormat('H:i:s', $overtime->start_time);
                $endTime = Carbon::createFromFormat('H:i:s', $overtime->end_time);
                
                // Handle overnight overtime
                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $endTime->addDay();
                }
                
                // Calculate and round to nearest 0.05 (forward calculation: start to end)
                $totalHours = $startTime->floatDiffInHours($endTime);
                $totalHours = round($totalHours * 20) / 20;
                
                if ($overtime->total_hours != $totalHours) {
                    $overtime->total_hours = $totalHours;
                    $overtime->save();
                    $updated++;
                    $this->line("Updated Overtime #{$overtime->id}: {$overtime->start_time} - {$overtime->end_time} = {$totalHours} hours");
                }
            }
        }
        
        $this->info("Recalculation complete! Updated {$updated} overtime requests.");
        
        return Command::SUCCESS;
    }
}
