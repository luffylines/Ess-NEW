<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->unsignedInteger('radius_meters')->default(50);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('allowed_networks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('ip_ranges'); // JSON array of IPs/CIDRs
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allowed_networks');
        Schema::dropIfExists('stores');
    }
};
