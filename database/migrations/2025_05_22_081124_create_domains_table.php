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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name')->unique();
            $table->foreignId('user_id')->constrained(); // ai sở hữu cái này có thể liên kết đến bảng khách hàng của bên mình
            $table->string('registrar')->nullable(); // Nhà đăng ký (e.g. Namecheap)
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('active'); // active, expired, suspended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
