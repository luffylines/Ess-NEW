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
        Schema::table('users', function (Blueprint $table) {
            // Add employee_id and role if they don't exist
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id', 50)->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['employee', 'hr', 'manager', 'admin'])->default('employee')->after('employee_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'role']);
        });
    }
};
