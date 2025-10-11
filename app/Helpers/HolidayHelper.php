<?php

namespace App\Helpers;

use App\Models\Holiday;
use Carbon\Carbon;

class HolidayHelper
{
    /**
     * Check if a date qualifies for double pay (holiday pay)
     */
    public static function isDoublePayDay($date, $country = 'PH', $region = null)
    {
        return Holiday::isDoublePayDay($date, $country, $region);
    }

    /**
     * Calculate overtime pay with holiday considerations
     */
    public static function calculateOvertimePay($date, $hours, $hourlyRate, $country = 'PH', $region = null)
    {
        $baseOvertimeRate = $hourlyRate * 1.25; // 25% overtime premium
        
        if (self::isDoublePayDay($date, $country, $region)) {
            // Double pay for holidays + overtime premium
            $holidayRate = $hourlyRate * 2; // Double the base rate
            $overtimePay = $holidayRate * 1.25 * $hours; // Add 25% overtime premium on holiday rate
        } else {
            $overtimePay = $baseOvertimeRate * $hours;
        }
        
        return [
            'base_rate' => $hourlyRate,
            'overtime_rate' => self::isDoublePayDay($date, $country, $region) ? $hourlyRate * 2.5 : $baseOvertimeRate,
            'hours' => $hours,
            'total_pay' => $overtimePay,
            'is_holiday' => self::isDoublePayDay($date, $country, $region),
            'pay_type' => self::isDoublePayDay($date, $country, $region) ? 'holiday_overtime' : 'regular_overtime'
        ];
    }

    /**
     * Calculate regular pay with holiday considerations
     */
    public static function calculateDailyPay($date, $hours, $hourlyRate, $country = 'PH', $region = null)
    {
        if (self::isDoublePayDay($date, $country, $region)) {
            $totalPay = $hourlyRate * 2 * $hours; // Double pay for holidays
            $payType = 'holiday_pay';
        } else {
            $totalPay = $hourlyRate * $hours; // Regular pay
            $payType = 'regular_pay';
        }
        
        return [
            'base_rate' => $hourlyRate,
            'effective_rate' => self::isDoublePayDay($date, $country, $region) ? $hourlyRate * 2 : $hourlyRate,
            'hours' => $hours,
            'total_pay' => $totalPay,
            'is_holiday' => self::isDoublePayDay($date, $country, $region),
            'pay_type' => $payType
        ];
    }

    /**
     * Get working days between two dates (excluding weekends and holidays)
     */
    public static function getWorkingDaysBetween($startDate, $endDate, $country = 'PH', $region = null)
    {
        return Holiday::calculateWorkingDays($startDate, $endDate, $country, $region);
    }

    /**
     * Get remaining working days in current month from today
     */
    public static function getRemainingWorkingDaysThisMonth($country = 'PH', $region = null)
    {
        return Holiday::getRemainingWorkingDaysThisMonth($country, $region);
    }

    /**
     * Get all holidays in a date range
     */
    public static function getHolidaysInRange($startDate, $endDate, $country = 'PH', $region = null)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $query = Holiday::whereBetween('date', [$start, $end])
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
     * Check if today is a working day (not weekend or holiday)
     */
    public static function isTodayWorkingDay($country = 'PH', $region = null)
    {
        $today = Carbon::now();
        
        // Check if it's a weekend
        if ($today->isWeekend()) {
            return false;
        }
        
        // Check if it's a holiday
        if (Holiday::isHoliday($today, $country, $region)) {
            return false;
        }
        
        return true;
    }

    /**
     * Get next working day
     */
    public static function getNextWorkingDay($date = null, $country = 'PH', $region = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        do {
            $date->addDay();
        } while ($date->isWeekend() || Holiday::isHoliday($date, $country, $region));
        
        return $date;
    }

    /**
     * Get previous working day
     */
    public static function getPreviousWorkingDay($date = null, $country = 'PH', $region = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        do {
            $date->subDay();
        } while ($date->isWeekend() || Holiday::isHoliday($date, $country, $region));
        
        return $date;
    }

    /**
     * Calculate monthly working days (for payroll)
     */
    public static function getMonthlyWorkingDays($year, $month, $country = 'PH', $region = null)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        return self::getWorkingDaysBetween($startOfMonth, $endOfMonth, $country, $region);
    }

    /**
     * Get payroll summary for a month including holiday considerations
     */
    public static function getMonthlyPayrollSummary($userId, $year, $month, $hourlyRate, $country = 'PH', $region = null)
    {
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        // Get all attendance records for the month
        $attendances = \App\Models\Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
            
        $regularPay = 0;
        $holidayPay = 0;
        $overtimePay = 0;
        $regularHours = 0;
        $holidayHours = 0;
        $overtimeHours = 0;
        
        foreach ($attendances as $attendance) {
            $date = Carbon::parse($attendance->date);
            $isHoliday = Holiday::isHoliday($date, $country, $region);
            
            // Regular hours (8 hours max per day)
            $dailyHours = min(8, $attendance->total_hours ?? 0);
            
            if ($isHoliday) {
                $holidayPay += $dailyHours * $hourlyRate * 2;
                $holidayHours += $dailyHours;
            } else {
                $regularPay += $dailyHours * $hourlyRate;
                $regularHours += $dailyHours;
            }
            
            // Overtime calculation (hours > 8)
            if (($attendance->total_hours ?? 0) > 8) {
                $overtimeHoursDaily = ($attendance->total_hours ?? 0) - 8;
                $overtimeCalculation = self::calculateOvertimePay($date, $overtimeHoursDaily, $hourlyRate, $country, $region);
                $overtimePay += $overtimeCalculation['total_pay'];
                $overtimeHours += $overtimeHoursDaily;
            }
        }
        
        return [
            'regular_hours' => $regularHours,
            'holiday_hours' => $holidayHours,
            'overtime_hours' => $overtimeHours,
            'regular_pay' => $regularPay,
            'holiday_pay' => $holidayPay,
            'overtime_pay' => $overtimePay,
            'total_pay' => $regularPay + $holidayPay + $overtimePay,
            'working_days_in_month' => self::getMonthlyWorkingDays($year, $month, $country, $region),
            'holidays_in_month' => Holiday::getHolidaysForMonth($year, $month, $country, $region)
        ];
    }
}