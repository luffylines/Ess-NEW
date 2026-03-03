<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to modify enum values
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN sender_type ENUM('user', 'bot', 'employee', 'system') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values if needed
        DB::statement("ALTER TABLE chat_messages MODIFY COLUMN sender_type ENUM('employee', 'system') NOT NULL");
    }
};
