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
        Schema::create('hostings', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('domain_id')->nullable()->constrained();
            $table->string('provider'); // VD: PAVietnam, Hostinger
            $table->string('package')->nullable(); // Gói hosting
            $table->string('ip_address')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('control_panel')->nullable(); // cPanel, DirectAdmin, Plesk
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostings');
    }
};
