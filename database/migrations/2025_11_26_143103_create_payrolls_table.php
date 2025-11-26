<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->year('pay_period_year');
            $table->tinyInteger('pay_period_month')->unsigned();
            $table->date('pay_period_start');
            $table->date('pay_period_end');
            
            // Basic Pay Calculation
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->integer('working_days')->default(0);
            $table->integer('days_worked')->default(0);
            $table->decimal('basic_pay', 10, 2)->default(0);
            
            // Overtime Calculation
            $table->decimal('regular_overtime_hours', 8, 2)->default(0);
            $table->decimal('holiday_overtime_hours', 8, 2)->default(0);
            $table->decimal('regular_overtime_pay', 10, 2)->default(0);
            $table->decimal('holiday_overtime_pay', 10, 2)->default(0);
            $table->decimal('total_overtime_pay', 10, 2)->default(0);
            
            // Attendance Deductions
            $table->decimal('late_hours', 8, 2)->default(0);
            $table->decimal('undertime_hours', 8, 2)->default(0);
            $table->decimal('absent_days', 8, 2)->default(0);
            $table->decimal('late_deductions', 10, 2)->default(0);
            $table->decimal('undertime_deductions', 10, 2)->default(0);
            $table->decimal('absent_deductions', 10, 2)->default(0);
            
            // Government Deductions
            $table->decimal('sss_contribution', 10, 2)->default(0);
            $table->decimal('philhealth_contribution', 10, 2)->default(0);
            $table->decimal('pagibig_contribution', 10, 2)->default(0);
            $table->decimal('withholding_tax', 10, 2)->default(0);
            
            // Other Deductions
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->text('other_deductions_notes')->nullable();
            
            // Totals
            $table->decimal('gross_pay', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('net_pay', 10, 2)->default(0);
            
            // Status and Approval
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'paid'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Metadata
            $table->text('calculation_notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'pay_period_year', 'pay_period_month']);
            $table->index('status');
            $table->unique(['user_id', 'pay_period_year', 'pay_period_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
