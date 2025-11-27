<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'day_type', 'time_in', 'time_out', 'status', 'remarks', 
        'created_by', 'approved_by', 'approved_at', 'rejection_reason',
        'breaktime_in', 'breaktime_out', 'total_hours', 'regular_hours', 
        'deduction_hours', 'deduction_amount', 'daily_rate', 'earned_amount'
    ];

    // Use $casts instead of $dates for Laravel 7+
    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'approved_at' => 'datetime',
        'breaktime_in' => 'datetime',
        'breaktime_out' => 'datetime',
        'total_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'deduction_hours' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'earned_amount' => 'decimal:2',
    ];
    
    // In Attendance model
    public function createdByUser() 
    {   
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedByUser() 
    {   
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate total working hours (excluding break time)
     */
    public function calculateTotalHours()
    {
        if (!$this->time_in || !$this->time_out) {
            return 0;
        }

        $timeIn = $this->time_in;
        $timeOut = $this->time_out;
        
        // Calculate total time (including break)
        $totalMinutes = $timeIn->diffInMinutes($timeOut);
        
        // Subtract break time if both breaktime_in and breaktime_out are set
        if ($this->breaktime_in && $this->breaktime_out) {
            $breakMinutes = $this->breaktime_in->diffInMinutes($this->breaktime_out);
            $totalMinutes -= $breakMinutes;
        }
        
        return round($totalMinutes / 60, 2);
    }

    /**
     * Calculate deductions based on hours worked
     */
    public function calculateDeductions()
    {
        $totalHours = $this->calculateTotalHours();
        $standardHours = 8.0;
        $dailyRate = $this->daily_rate ?? 600;
        $hourlyRate = $dailyRate / $standardHours;
        
        if ($totalHours >= $standardHours) {
            // No deduction, full pay or even overtime
            $this->regular_hours = $standardHours;
            $this->deduction_hours = 0;
            $this->deduction_amount = 0;
            $this->earned_amount = $dailyRate;
        } elseif ($totalHours >= 7) {
            // Partial deduction for 7-7.99 hours
            $this->regular_hours = $totalHours;
            $this->deduction_hours = $standardHours - $totalHours;
            $this->deduction_amount = $this->deduction_hours * $hourlyRate;
            $this->earned_amount = $totalHours * $hourlyRate;
        } else {
            // Significant deduction for <7 hours
            $this->regular_hours = $totalHours;
            $this->deduction_hours = $standardHours - $totalHours;
            $this->deduction_amount = $this->deduction_hours * $hourlyRate;
            $this->earned_amount = $totalHours * $hourlyRate;
        }
        
        $this->total_hours = $totalHours;
    }

    /**
     * Auto-calculate hours and deductions before saving
     */
    protected static function booted()
    {
        static::saving(function ($attendance) {
            if ($attendance->time_in && $attendance->time_out) {
                $attendance->calculateDeductions();
            }
        });
    }
}
