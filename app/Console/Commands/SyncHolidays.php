<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HolidaySyncService;
use Carbon\Carbon;

class SyncHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:sync 
                            {--year= : The year to sync holidays for (default: current year)}
                            {--start-year= : Start year for multiple years sync}
                            {--end-year= : End year for multiple years sync}
                            {--country=PH : Country code to sync holidays for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync public holidays from external API';

    private $holidayService;

    public function __construct(HolidaySyncService $holidayService)
    {
        parent::__construct();
        $this->holidayService = $holidayService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting holiday synchronization...');

        $startYear = $this->option('start-year');
        $endYear = $this->option('end-year');
        $year = $this->option('year');
        $country = $this->option('country');

        try {
            if ($startYear && $endYear) {
                // Sync multiple years
                $this->info("Syncing holidays for years {$startYear} to {$endYear}...");
                $results = $this->holidayService->syncMultipleYears($startYear, $endYear);
                
                foreach ($results as $yr => $result) {
                    if ($result['success']) {
                        $this->info("✓ {$yr}: {$result['message']}");
                    } else {
                        $this->error("✗ {$yr}: Failed to sync holidays");
                    }
                }
            } else {
                // Sync single year
                $targetYear = $year ?? Carbon::now()->year;
                $this->info("Syncing holidays for year {$targetYear}...");
                
                $result = $this->holidayService->syncHolidays($targetYear);
                
                if ($result['success']) {
                    $this->info("✓ {$result['message']}");
                } else {
                    $this->error("✗ Failed to sync holidays");
                }
            }

            $this->info('Holiday synchronization completed!');
            
            // Show upcoming holidays
            $this->showUpcomingHolidays();
            
        } catch (\Exception $e) {
            $this->error("Holiday sync failed: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function showUpcomingHolidays()
    {
        $this->info("\n--- Upcoming Holidays (Next 60 days) ---");
        
        $upcomingHolidays = $this->holidayService->getUpcomingHolidays(60);
        
        if ($upcomingHolidays->count() > 0) {
            $headers = ['Date', 'Holiday Name', 'Type'];
            $rows = [];
            
            foreach ($upcomingHolidays as $holiday) {
                $rows[] = [
                    Carbon::parse($holiday->date)->format('M d, Y (D)'),
                    $holiday->name,
                    ucfirst($holiday->type)
                ];
            }
            
            $this->table($headers, $rows);
        } else {
            $this->info('No upcoming holidays found in the next 60 days.');
        }
    }
}
