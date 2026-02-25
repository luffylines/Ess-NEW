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
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->date('schedule_date');
            $table->time('shift_start');
            $table->time('shift_end');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->string('shift_type')->default('regular'); // regular, overtime, holiday
            $table->string('location')->nullable(); // Store location or work from home
            $table->text('notes')->nullable();
            $table->enum('status', ['assigned', 'acknowledged', 'completed', 'missed'])->default('assigned');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
            
            // Index for better performance
            $table->index(['employee_id', 'schedule_date']);
            $table->index(['assigned_by', 'schedule_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};