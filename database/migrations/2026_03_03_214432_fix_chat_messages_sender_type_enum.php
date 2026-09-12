<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PostgreSQL: use VARCHAR with a CHECK constraint instead of MySQL ENUM syntax.
        DB::statement("\n            ALTER TABLE chat_messages\n            DROP CONSTRAINT IF EXISTS chat_messages_sender_type_check\n        ");

        DB::statement("\n            ALTER TABLE chat_messages\n            ALTER COLUMN sender_type TYPE VARCHAR(20)\n            USING sender_type::text\n        ");

        DB::statement("\n            ALTER TABLE chat_messages\n            ADD CONSTRAINT chat_messages_sender_type_check\n            CHECK (sender_type IN ('user', 'bot', 'employee', 'system'))\n        ");

        DB::statement("\n            ALTER TABLE chat_messages\n            ALTER COLUMN sender_type SET NOT NULL\n        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("\n            ALTER TABLE chat_messages\n            DROP CONSTRAINT IF EXISTS chat_messages_sender_type_check\n        ");

        // Remove values that are not supported by the original migration.
        DB::statement("\n            DELETE FROM chat_messages\n            WHERE sender_type NOT IN ('employee', 'system')\n        ");

        DB::statement("\n            ALTER TABLE chat_messages\n            ADD CONSTRAINT chat_messages_sender_type_check\n            CHECK (sender_type IN ('employee', 'system'))\n        ");
    }
};
