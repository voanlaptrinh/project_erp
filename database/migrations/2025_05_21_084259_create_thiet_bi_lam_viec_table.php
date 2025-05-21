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
        Schema::create('thiet_bi_lam_viec', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('loai_thiet_bi'); // PC, laptop, tablet, ...
            $table->string('ten_thiet_bi')->nullable(); // Tên/mã thiết bị
            $table->string('he_dieu_hanh')->nullable(); // Windows, macOS, Android, ...
            $table->string('cau_hinh')->nullable(); // Thông tin cấu hình
            $table->string('so_serial')->nullable(); // Số serial thiết bị
            $table->date('ngay_ban_giao')->nullable(); // Ngày bàn giao thiết bị
            $table->text('ghi_chu')->nullable(); // Ghi chú thêm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thiet_bi_lam_viec');
    }
};