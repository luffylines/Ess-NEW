<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\HolidayHelper;
use App\Models\User;
use Carbon\Carbon;

class Payroll extends Model
{
    protected $fillable = [
        'user_id',
        'pay_period_year',
        'pay_period_month',
        'pay_period_start',
        'pay_period_end',
        'daily_rate',
        'working_days',
        'days_worked',
        'basic_pay',
        'regular_overtime_hours',
        'holiday_overtime_hours',
        'regular_overtime_pay',
        'holiday_overtime_pay',
        'total_overtime_pay',
        'late_hours',
        'undertime_hours',
        'absent_days',
        'late_deductions',
        'undertime_deductions',
        'absent_deductions',
        'sss_contribution',
        'philhealth_contribution',
        'pagibig_contribution',
        'withholding_tax',
        'other_deductions',
        'other_deductions_notes',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'status',
        'approved_by',
        'approved_at',
        'paid_at',
        'calculation_notes'
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'daily_rate' => 'decimal:2',
        'basic_pay' => 'decimal:2',
        'regular_overtime_hours' => 'decimal:2',
        'holiday_overtime_hours' => 'decimal:2',
        'regular_overtime_pay' => 'decimal:2',
        'holiday_overtime_pay' => 'decimal:2',
        'total_overtime_pay' => 'decimal:2',
        'late_hours' => 'decimal:2',
        'undertime_hours' => 'decimal:2',
        'absent_days' => 'decimal:2',
        'late_deductions' => 'decimal:2',
        'undertime_deductions' => 'decimal:2',
        'absent_deductions' => 'decimal:2',
        'sss_contribution' => 'decimal:2',
        'philhealth_contribution' => 'decimal:2',
        'pagibig_contribution' => 'decimal:2',
        'withholding_tax' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    // Scopes
    public function scopeForPeriod($query, $year, $month)
    {
        return $query->where('pay_period_year', $year)
                    ->where('pay_period_month', $month);
    }

    // Status Methods
    public function isPending(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // Employee Info Accessors
    public function getEmployeeNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }

    public function getEmployeeIdAttribute(): string
    {
        return $this->user->employee_id ?? 'N/A';
    }

    public function getEmployeePositionAttribute(): string
    {
        return $this->user->role ?? 'Employee';
    }

    public function getEmployeeDepartmentAttribute(): string
    {
        return $this->user->department ?? 'Salon';
    }

    public function getStatusBadge(): string
    {
        $colors = [
            'draft' => 'bg-secondary',
            'pending_approval' => 'bg-warning text-dark',
            'approved' => 'bg-success',
            'paid' => 'bg-primary'
        ];

        $color = $colors[$this->status] ?? 'bg-secondary';
        return '<span class="badge ' . $color . '">' . ucwords(str_replace('_', ' ', $this->status)) . '</span>';
    }

    // Calculate payroll for a user
    public static function calculatePayroll($userId, $year, $month, $dailyRate = null)
    {
        $user = User::findOrFail($userId);
        
        // Get daily rate from user or use default based on role
        if (!$dailyRate) {
            // Default rates based on common Philippine rates
            $defaultRates = [
                'manager' => 1000,
                'hr' => 800,
                'employee' => 600
            ];
            $dailyRate = $defaultRates[$user->role] ?? 600;
        }
        
        $payroll = new self();
        
        // Set pay period
        $payPeriodStart = Carbon::create($year, $month, 1);
        $payPeriodEnd = $payPeriodStart->copy()->endOfMonth();
        
        $payroll->fill([
            'user_id' => $userId,
            'pay_period_year' => $year,
            'pay_period_month' => $month,
            'pay_period_start' => $payPeriodStart,
            'pay_period_end' => $payPeriodEnd,
            'daily_rate' => $dailyRate,
        ]);

        // Calculate working days in month
        $workingDays = HolidayHelper::getMonthlyWorkingDays($year, $month);
        $payroll->working_days = $workingDays;

        // Get attendance data
        $attendances = \App\Models\Attendance::where('user_id', $userId)
            ->whereBetween('date', [$payPeriodStart->format('Y-m-d'), $payPeriodEnd->format('Y-m-d')])
            ->where('status', '!=', 'absent')
            ->get();

        $payroll->days_worked = $attendances->count();
        $payroll->basic_pay = $payroll->daily_rate * $payroll->days_worked;

        // Calculate overtime
        $overtimeRequests = \App\Models\OvertimeRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereBetween('overtime_date', [$payPeriodStart->format('Y-m-d'), $payPeriodEnd->format('Y-m-d')])
            ->get();

        $regularHours = 0;
        $holidayHours = 0;
        $hourlyRate = $dailyRate / 8;

        foreach ($overtimeRequests as $overtime) {
            $isHoliday = HolidayHelper::isDoublePayDay($overtime->overtime_date);
            if ($isHoliday) {
                $holidayHours += $overtime->total_hours;
            } else {
                $regularHours += $overtime->total_hours;
            }
        }

        $payroll->regular_overtime_hours = $regularHours;
        $payroll->holiday_overtime_hours = $holidayHours;
        $payroll->regular_overtime_pay = $regularHours * $hourlyRate * 1.25;
        $payroll->holiday_overtime_pay = $holidayHours * $hourlyRate * 2.5;
        $payroll->total_overtime_pay = $payroll->regular_overtime_pay + $payroll->holiday_overtime_pay;

        // Calculate gross pay
        $payroll->gross_pay = $payroll->basic_pay + $payroll->total_overtime_pay;

        // Calculate government deductions (simplified)
        $payroll->sss_contribution = $payroll->calculateSSS($payroll->gross_pay);
        $payroll->philhealth_contribution = $payroll->calculatePhilHealth($payroll->gross_pay);
        $payroll->pagibig_contribution = $payroll->calculatePagibig($payroll->gross_pay);
        $payroll->withholding_tax = $payroll->calculateWithholdingTax($payroll->gross_pay);

        // Calculate total deductions
        $payroll->total_deductions = $payroll->sss_contribution + $payroll->philhealth_contribution + 
                                   $payroll->pagibig_contribution + $payroll->withholding_tax + 
                                   $payroll->other_deductions;

        // Calculate net pay
        $payroll->net_pay = $payroll->gross_pay - $payroll->total_deductions;

        return $payroll;
    }

    private function calculateSSS($grossPay)
    {
        if ($grossPay <= 4250) return 180;
        if ($grossPay <= 4750) return 202.50;
        if ($grossPay <= 5250) return 225;
        if ($grossPay <= 5750) return 247.50;
        if ($grossPay <= 6250) return 270;
        if ($grossPay <= 6750) return 292.50;
        if ($grossPay <= 7250) return 315;
        if ($grossPay <= 7750) return 337.50;
        if ($grossPay <= 8250) return 360;
        if ($grossPay <= 8750) return 382.50;
        if ($grossPay <= 9250) return 405;
        if ($grossPay <= 9750) return 427.50;
        return 450; // Maximum
    }

    private function calculatePhilHealth($grossPay)
    {
        $premium = $grossPay * 0.03; // 3% of basic salary
        return min(max($premium / 2, 150), 1800); // Employee share: minimum 150, maximum 1800
    }

    private function calculatePagibig($grossPay)
    {
        return min($grossPay * 0.02, 100); // 2% max of 100
    }

    private function calculateWithholdingTax($grossPay)
    {
        // Simplified calculation
        $annualizedSalary = $grossPay * 12;
        if ($annualizedSalary <= 250000) return 0;
        if ($annualizedSalary <= 400000) return ($annualizedSalary - 250000) * 0.15 / 12;
        return (22500 + ($annualizedSalary - 400000) * 0.20) / 12;
    }

    public function approve($approvedBy)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now()
        ]);
    }
    
    // Accessors for overtime rates
    public function getRegularOvertimeRateAttribute()
    {
        $hourlyRate = $this->daily_rate / 8; // 8 hours per day
        return $hourlyRate * 1.25; // 125% for regular overtime
    }
    
    public function getHolidayOvertimeRateAttribute()
    {
        $hourlyRate = $this->daily_rate / 8; // 8 hours per day
        return $hourlyRate * 2.5; // 250% for holiday overtime
    }
    
    public function getHourlyRateAttribute()
    {
        return $this->daily_rate / 8;
    }
    
    // Recalculated overtime pay (in case stored values are incorrect)
    public function getCalculatedRegularOvertimePayAttribute()
    {
        return $this->regular_overtime_hours * $this->regular_overtime_rate;
    }
    
    public function getCalculatedHolidayOvertimePayAttribute()
    {
        return $this->holiday_overtime_hours * $this->holiday_overtime_rate;
    }
    
    public function getCalculatedTotalOvertimePayAttribute()
    {
        return $this->calculated_regular_overtime_pay + $this->calculated_holiday_overtime_pay;
    }
    
    public function getCalculatedGrossPayAttribute()
    {
        return $this->basic_pay + $this->calculated_total_overtime_pay;
    }
    
    public function getCalculatedNetPayAttribute()
    {
        return $this->calculated_gross_pay - $this->total_deductions;
    }
    
    // Method to recalculate and update stored overtime values
    public function recalculateOvertimePay()
    {
        $this->regular_overtime_pay = $this->calculated_regular_overtime_pay;
        $this->holiday_overtime_pay = $this->calculated_holiday_overtime_pay;
        $this->total_overtime_pay = $this->calculated_total_overtime_pay;
        $this->gross_pay = $this->calculated_gross_pay;
        $this->net_pay = $this->calculated_net_pay;
        return $this->save();
    }
}
