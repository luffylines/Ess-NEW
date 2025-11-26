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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payslip_number')->unique();
            $table->year('pay_period_year');
            $table->tinyInteger('pay_period_month')->unsigned();
            $table->date('pay_period_start');
            $table->date('pay_period_end');
            $table->date('generated_date');
            
            // Employee Information at time of generation
            $table->string('employee_name');
            $table->string('employee_id');
            $table->string('employee_position')->nullable();
            $table->string('employee_department')->nullable();
            
            // Pay Summary (copied from payroll for historical accuracy)
            $table->decimal('basic_pay', 10, 2);
            $table->decimal('total_overtime_pay', 10, 2);
            $table->decimal('gross_pay', 10, 2);
            $table->decimal('total_deductions', 10, 2);
            $table->decimal('net_pay', 10, 2);
            
            // File Storage
            $table->string('pdf_path')->nullable();
            $table->boolean('is_downloaded')->default(false);
            $table->timestamp('first_downloaded_at')->nullable();
            $table->integer('download_count')->default(0);
            
            // Status
            $table->enum('status', ['generated', 'sent', 'viewed'])->default('generated');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'pay_period_year', 'pay_period_month']);
            $table->index('status');
            $table->index('payslip_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
