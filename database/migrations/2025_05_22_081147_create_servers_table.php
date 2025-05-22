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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_name');
            $table->foreignId('user_id')->constrained();
            $table->string('provider'); // VD: DigitalOcean, Vultr
            $table->string('ip_address');
            $table->string('os')->nullable();
            $table->string('login_user')->nullable();
            $table->string('login_password')->nullable(); // nên mã hóa
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
