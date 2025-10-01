<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_otps_table.php
    public function up()
    {
    Schema::create('otps', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');   // link to users table
        $table->string('otp_code');
        $table->timestamp('expires_at'); // 60 seconds validity
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
