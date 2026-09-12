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
        // The existing chat_messages migration does not create the chat metadata
        // columns used by the Chat model/controller. Create the missing chats table
        // and add the required PostgreSQL-compatible columns here.
        if (!Schema::hasTable('chats')) {
            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('hr_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status')->default('active');
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('chat_messages', 'chat_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->foreignId('chat_id')->nullable()->after('id')->constrained('chats')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('chat_messages', 'sender_type')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->string('sender_type', 20)->default('user')->after('message');
            });
        }

        if (!Schema::hasColumn('chat_messages', 'is_read')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->boolean('is_read')->default(false)->after('sender_type');
            });
        }

        // PostgreSQL: enforce the same allowed sender types that the application expects.
        DB::statement('ALTER TABLE chat_messages DROP CONSTRAINT IF EXISTS chat_messages_sender_type_check');
        DB::statement("ALTER TABLE chat_messages ADD CONSTRAINT chat_messages_sender_type_check CHECK (sender_type IN ('user', 'bot', 'employee', 'system'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE chat_messages DROP CONSTRAINT IF EXISTS chat_messages_sender_type_check');

        if (Schema::hasColumn('chat_messages', 'chat_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropConstrainedForeignId('chat_id');
            });
        }

        if (Schema::hasColumn('chat_messages', 'is_read')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }

        if (Schema::hasColumn('chat_messages', 'sender_type')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropColumn('sender_type');
            });
        }

        if (Schema::hasTable('chats')) {
            Schema::drop('chats');
        }
    }
};
