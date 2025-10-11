<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HolidaySyncService
{
    private $apiKey;
    private $country;

    public function __construct()
    {
        $this->apiKey = config('services.calendarific.api_key');
        $this->country = config('services.calendarific.country', 'PH');
    }

    /**
     * Sync holidays from Calendarific API
     */
    public function syncHolidays($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        
        try {
            // Using Calendarific API (free tier available)
            $response = Http::get('https://calendarific.com/api/v2/holidays', [
                'api_key' => $this->apiKey,
                'country' => $this->country,
                'year' => $year,
                'type' => 'national,local'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->saveHolidays($data['response']['holidays'], $year);
                
                Log::info("Successfully synced holidays for year {$year}");
                return ['success' => true, 'message' => "Holidays synced for {$year}"];
            } else {
                // Fallback to manual Philippine holidays if API fails
                $this->createPhilippineHolidays($year);
                Log::warning("API failed, created manual Philippine holidays for {$year}");
                return ['success' => true, 'message' => "Manual holidays created for {$year}"];
            }
        } catch (\Exception $e) {
            Log::error("Holiday sync failed: " . $e->getMessage());
            
            // Fallback to manual holidays
            $this->createPhilippineHolidays($year);
            return ['success' => true, 'message' => "Manual holidays created due to API error"];
        }
    }

    /**
     * Save holidays from API response
     */
    private function saveHolidays($holidays, $year)
    {
        foreach ($holidays as $holiday) {
            $date = Carbon::parse($holiday['date']['iso']);
            
            Holiday::updateOrCreate(
                [
                    'date' => $date->format('Y-m-d'),
                    'country' => $this->country
                ],
                [
                    'name' => $holiday['name'],
                    'type' => $this->mapHolidayType($holiday['type']),
                    'description' => $holiday['description'] ?? null,
                    'is_active' => true
                ]
            );
        }
    }

    /**
     * Create manual Philippine holidays (fallback)
     */
    private function createPhilippineHolidays($year)
    {
        $holidays = [
            // Fixed holidays
            ['date' => "{$year}-01-01", 'name' => "New Year's Day", 'type' => 'regular'],
            ['date' => "{$year}-04-09", 'name' => "Araw ng Kagitingan", 'type' => 'regular'],
            ['date' => "{$year}-05-01", 'name' => "Labor Day", 'type' => 'regular'],
            ['date' => "{$year}-06-12", 'name' => "Independence Day", 'type' => 'regular'],
            ['date' => "{$year}-08-21", 'name' => "Ninoy Aquino Day", 'type' => 'special'],
            ['date' => "{$year}-08-28", 'name' => "National Heroes Day", 'type' => 'regular'],
            ['date' => "{$year}-11-30", 'name' => "Bonifacio Day", 'type' => 'regular'],
            ['date' => "{$year}-12-25", 'name' => "Christmas Day", 'type' => 'regular'],
            ['date' => "{$year}-12-30", 'name' => "Rizal Day", 'type' => 'regular'],
            ['date' => "{$year}-12-31", 'name' => "New Year's Eve", 'type' => 'special'],
            
            // Variable holidays (approximate dates - should be calculated properly)
            ['date' => $this->calculateMaundyThursday($year), 'name' => "Maundy Thursday", 'type' => 'regular'],
            ['date' => $this->calculateGoodFriday($year), 'name' => "Good Friday", 'type' => 'regular'],
            ['date' => $this->calculateBlackSaturday($year), 'name' => "Black Saturday", 'type' => 'special'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                [
                    'date' => $holiday['date'],
                    'country' => 'PH'
                ],
                [
                    'name' => $holiday['name'],
                    'type' => $holiday['type'],
                    'is_active' => true
                ]
            );
        }
    }

    /**
     * Calculate Easter-based holidays
     */
    private function calculateMaundyThursday($year)
    {
        return Carbon::parse("$year-03-21")->addDays(easter_days($year) - 3)->format('Y-m-d');
    }

    private function calculateGoodFriday($year)
    {
        return Carbon::parse("$year-03-21")->addDays(easter_days($year) - 2)->format('Y-m-d');
    }

    private function calculateBlackSaturday($year)
    {
        return Carbon::parse("$year-03-21")->addDays(easter_days($year) - 1)->format('Y-m-d');
    }

    /**
     * Map API holiday types to our system
     */
    private function mapHolidayType($apiType)
    {
        $typeMap = [
            'national' => 'regular',
            'local' => 'local',
            'religious' => 'special',
            'observance' => 'special'
        ];

        return $typeMap[$apiType] ?? 'regular';
    }

    /**
     * Sync multiple years
     */
    public function syncMultipleYears($startYear, $endYear)
    {
        $results = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $results[$year] = $this->syncHolidays($year);
        }
        
        return $results;
    }

    /**
     * Get upcoming holidays
     */
    public function getUpcomingHolidays($days = 30, $country = 'PH')
    {
        $today = Carbon::now();
        $futureDate = $today->copy()->addDays($days);
        
        return Holiday::whereBetween('date', [$today, $futureDate])
            ->where('country', $country)
            ->where('is_active', true)
            ->orderBy('date')
            ->get();
    }

    /**
     * Check if today is a holiday
     */
    public function isTodayHoliday($country = 'PH')
    {
        return Holiday::isHoliday(Carbon::now(), $country);
    }
}