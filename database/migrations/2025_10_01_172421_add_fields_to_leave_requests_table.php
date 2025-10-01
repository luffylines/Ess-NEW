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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->text('reason')->after('end_date');
            $table->integer('total_days')->after('reason');
            $table->string('supporting_document')->nullable()->after('total_days');
            $table->text('manager_remarks')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('manager_remarks');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'reason',
                'total_days', 
                'supporting_document',
                'manager_remarks',
                'approved_by',
                'approved_at'
            ]);
        });
    }
};
