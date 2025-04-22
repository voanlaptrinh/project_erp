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
        Schema::create('bao_gias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hop_dong_id');
            $table->string('so_bao_gia')->unique(); //mã báo giá
            $table->date('ngay_gui')->nullable();
            $table->decimal('tong_gia_tri', 15, 2)->nullable();
            $table->text('chi_tiet')->nullable();
            $table->string('trang_thai')->default('đang chờ'); // Chấp nhận, Từ chối
            $table->timestamps();

            $table->foreign('hop_dong_id')->references('id')->on('hop_dongs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_gias');
    }
};
