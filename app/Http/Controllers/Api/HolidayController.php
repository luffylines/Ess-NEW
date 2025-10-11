<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Helpers\HolidayHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Check if a specific date is a holiday
     */
    public function checkHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'country' => 'string|size:2',
            'region' => 'string|nullable'
        ]);

        $date = $request->input('date');
        $country = $request->input('country', 'PH');
        $region = $request->input('region');

        $isHoliday = Holiday::isHoliday($date, $country, $region);
        $isDoublePayDay = HolidayHelper::isDoublePayDay($date, $country, $region);

        $holiday = null;
        if ($isHoliday) {
            $holiday = Holiday::where('date', Carbon::parse($date)->format('Y-m-d'))
                ->where('country', $country)
                ->where('is_active', true)
                ->first();
        }

        return response()->json([
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'is_holiday' => $isHoliday,
            'is_double_pay_day' => $isDoublePayDay,
            'is_working_day' => HolidayHelper::isTodayWorkingDay($country, $region),
            'holiday_details' => $holiday ? [
                'name' => $holiday->name,
                'type' => $holiday->type,
                'description' => $holiday->description
            ] : null
        ]);
    }

    /**
     * Get remaining working days in current month
     */
    public function remainingWorkingDays(Request $request)
    {
        $country = $request->input('country', 'PH');
        $region = $request->input('region');

        $remainingDays = HolidayHelper::getRemainingWorkingDaysThisMonth($country, $region);
        $today = Carbon::now();
        $endOfMonth = Carbon::now()->endOfMonth();

        return response()->json([
            'today' => $today->format('Y-m-d'),
            'end_of_month' => $endOfMonth->format('Y-m-d'),
            'remaining_working_days' => $remainingDays,
            'total_days_remaining' => $today->diffInDays($endOfMonth),
            'country' => $country,
            'region' => $region
        ]);
    }

    /**
     * Calculate overtime pay with holiday considerations
     */
    public function calculateOvertimePay(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'country' => 'string|size:2',
            'region' => 'string|nullable'
        ]);

        $date = $request->input('date');
        $hours = $request->input('hours');
        $hourlyRate = $request->input('hourly_rate');
        $country = $request->input('country', 'PH');
        $region = $request->input('region');

        $calculation = HolidayHelper::calculateOvertimePay(
            $date, 
            $hours, 
            $hourlyRate, 
            $country, 
            $region
        );

        return response()->json([
            'date' => Carbon::parse($date)->format('Y-m-d'),
            'calculation' => $calculation
        ]);
    }

    /**
     * Get holidays in a date range
     */
    public function getHolidays(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'country' => 'string|size:2',
            'region' => 'string|nullable'
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $country = $request->input('country', 'PH');
        $region = $request->input('region');

        $holidays = HolidayHelper::getHolidaysInRange($startDate, $endDate, $country, $region);

        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'country' => $country,
            'region' => $region,
            'holidays' => $holidays->map(function ($holiday) {
                return [
                    'date' => $holiday->date->format('Y-m-d'),
                    'name' => $holiday->name,
                    'type' => $holiday->type,
                    'day_of_week' => $holiday->date->format('l'),
                    'description' => $holiday->description
                ];
            }),
            'total_holidays' => $holidays->count(),
            'working_days' => HolidayHelper::getWorkingDaysBetween($startDate, $endDate, $country, $region)
        ]);
    }

    /**
     * Get upcoming holidays
     */
    public function upcomingHolidays(Request $request)
    {
        $days = $request->input('days', 30);
        $country = $request->input('country', 'PH');
        
        $today = Carbon::now();
        $futureDate = $today->copy()->addDays($days);
        
        $holidays = Holiday::whereBetween('date', [$today, $futureDate])
            ->where('country', $country)
            ->where('is_active', true)
            ->orderBy('date')
            ->get();

        return response()->json([
            'from_date' => $today->format('Y-m-d'),
            'to_date' => $futureDate->format('Y-m-d'),
            'days_ahead' => $days,
            'country' => $country,
            'holidays' => $holidays->map(function ($holiday) {
                return [
                    'date' => $holiday->date->format('Y-m-d'),
                    'name' => $holiday->name,
                    'type' => $holiday->type,
                    'day_of_week' => $holiday->date->format('l'),
                    'days_from_now' => Carbon::now()->diffInDays($holiday->date, false),
                    'description' => $holiday->description
                ];
            }),
            'total_upcoming_holidays' => $holidays->count()
        ]);
    }
}