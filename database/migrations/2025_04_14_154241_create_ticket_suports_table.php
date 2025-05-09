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
        Schema::create('ticket_suports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khach_hang_id');
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->enum('trang_thai', ['mới', 'đang xử lý', 'đã xử lý', 'đã đóng'])->default('mới');
            $table->enum('uu_tien', ['thấp', 'trung bình', 'cao', 'khẩn cấp'])->default('trung bình');
            $table->unsignedBigInteger('nguoi_xu_ly_id')->nullable(); // user_id xử lý
            $table->timestamps();
    
            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs')->cascadeOnDelete();
            $table->foreign('nguoi_xu_ly_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_suports');
    }
};
