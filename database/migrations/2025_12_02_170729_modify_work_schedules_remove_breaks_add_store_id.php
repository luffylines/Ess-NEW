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
        Schema::table('work_schedules', function (Blueprint $table) {
            // Remove break time columns
            $table->dropColumn(['break_start', 'break_end']);
            
            // Add store_id foreign key
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('set null')->after('shift_end');
            
            // Remove location text field (but keep it for now in case we need to migrate data)
            // We'll drop it after ensuring all data is migrated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_schedules', function (Blueprint $table) {
            // Add break time columns back
            $table->time('break_start')->nullable()->after('shift_end');
            $table->time('break_end')->nullable()->after('break_start');
            
            // Remove store_id foreign key
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};
