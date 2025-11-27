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
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('breaktime_in')->nullable()->after('time_out');
            $table->time('breaktime_out')->nullable()->after('breaktime_in');
            $table->decimal('total_hours', 4, 2)->default(0)->after('breaktime_out');
            $table->decimal('regular_hours', 4, 2)->default(0)->after('total_hours');
            $table->decimal('deduction_hours', 4, 2)->default(0)->after('regular_hours');
            $table->decimal('deduction_amount', 8, 2)->default(0)->after('deduction_hours');
            $table->decimal('daily_rate', 8, 2)->default(600)->after('deduction_amount');
            $table->decimal('earned_amount', 8, 2)->default(0)->after('daily_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'breaktime_in',
                'breaktime_out', 
                'total_hours',
                'regular_hours',
                'deduction_hours',
                'deduction_amount',
                'daily_rate',
                'earned_amount'
            ]);
        });
    }
};
