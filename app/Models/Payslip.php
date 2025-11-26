<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    protected $fillable = [
        'payroll_id',
        'user_id',
        'payslip_number',
        'pay_period_year',
        'pay_period_month',
        'pay_period_start',
        'pay_period_end',
        'generated_date',
        'employee_name',
        'employee_id',
        'employee_position',
        'employee_department',
        'basic_pay',
        'total_overtime_pay',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'pdf_path',
        'is_downloaded',
        'first_downloaded_at',
        'download_count',
        'status',
        'sent_at',
        'viewed_at'
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'generated_date' => 'date',
        'basic_pay' => 'decimal:2',
        'total_overtime_pay' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'is_downloaded' => 'boolean',
        'first_downloaded_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime'
    ];

    // Relationships
    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Generate unique payslip number
    public static function generatePayslipNumber($year, $month, $employeeId)
    {
        return sprintf('PS-%04d%02d-%s-%03d', 
            $year, 
            $month, 
            $employeeId, 
            self::where('pay_period_year', $year)
                ->where('pay_period_month', $month)
                ->count() + 1
        );
    }

    // Create payslip from payroll
    public static function createFromPayroll(Payroll $payroll)
    {
        $user = $payroll->user;
        
        return self::create([
            'payroll_id' => $payroll->id,
            'user_id' => $payroll->user_id,
            'payslip_number' => self::generatePayslipNumber(
                $payroll->pay_period_year, 
                $payroll->pay_period_month, 
                $user->employee_id
            ),
            'pay_period_year' => $payroll->pay_period_year,
            'pay_period_month' => $payroll->pay_period_month,
            'pay_period_start' => $payroll->pay_period_start,
            'pay_period_end' => $payroll->pay_period_end,
            'generated_date' => now()->toDateString(),
            'employee_name' => $user->name,
            'employee_id' => $user->employee_id,
            'employee_position' => $user->position ?? 'Employee',
            'employee_department' => $user->department ?? 'General',
            'basic_pay' => $payroll->basic_pay,
            'total_overtime_pay' => $payroll->total_overtime_pay,
            'gross_pay' => $payroll->gross_pay,
            'total_deductions' => $payroll->total_deductions,
            'net_pay' => $payroll->net_pay,
            'status' => 'generated'
        ]);
    }

    // Mark as downloaded
    public function markAsDownloaded()
    {
        if (!$this->is_downloaded) {
            $this->update([
                'is_downloaded' => true,
                'first_downloaded_at' => now()
            ]);
        }
        
        $this->increment('download_count');
    }

    // Mark as viewed
    public function markAsViewed()
    {
        if ($this->status === 'generated') {
            $this->update([
                'status' => 'viewed',
                'viewed_at' => now()
            ]);
        }
    }

    // Get formatted period
    public function getFormattedPeriod()
    {
        return $this->pay_period_start->format('M d') . ' - ' . $this->pay_period_end->format('M d, Y');
    }

    // Get download URL
    public function getDownloadUrl()
    {
        return route('payslips.download', $this);
    }

    // Get view URL
    public function getViewUrl()
    {
        return route('payslips.view', $this);
    }
}
