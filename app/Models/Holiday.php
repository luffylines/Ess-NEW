<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'type',
        'country',
        'region',
        'is_active'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Check if a given date is a holiday
     */
    public static function isHoliday($date, $country = 'PH', $region = null)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        
        $query = self::where('date', $date)
            ->where('is_active', true)
            ->where('country', $country);
            
        if ($region) {
            $query->where(function($q) use ($region) {
                $q->where('region', $region)->orWhereNull('region');
            });
        }
        
        return $query->exists();
    }

    /**
     * Get holidays for a specific month and year
     */
    public static function getHolidaysForMonth($year, $month, $country = 'PH', $region = null)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $query = self::whereBetween('date', [$startDate, $endDate])
            ->where('is_active', true)
            ->where('country', $country);
            
        if ($region) {
            $query->where(function($q) use ($region) {
                $q->where('region', $region)->orWhereNull('region');
            });
        }
        
        return $query->orderBy('date')->get();
    }

    /**
     * Check if overtime should be paid double due to holiday
     */
    public static function isDoublePayDay($date, $country = 'PH', $region = null)
    {
        return self::isHoliday($date, $country, $region);
    }

    /**
     * Calculate working days excluding weekends and holidays
     */
    public static function calculateWorkingDays($startDate, $endDate, $country = 'PH', $region = null)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;
        
        $holidays = self::whereBetween('date', [$start, $end])
            ->where('is_active', true)
            ->where('country', $country)
            ->pluck('date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();
        
        while ($start->lte($end)) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if (!$start->isWeekend() && !in_array($start->format('Y-m-d'), $holidays)) {
                $workingDays++;
            }
            $start->addDay();
        }
        
        return $workingDays;
    }

    /**
     * Get remaining working days in current month from today
     */
    public static function getRemainingWorkingDaysThisMonth($country = 'PH', $region = null)
    {
        $today = Carbon::now();
        $tomorrow = Carbon::now()->addDay();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        return self::calculateWorkingDays($tomorrow, $endOfMonth, $country, $region);
    }
}
