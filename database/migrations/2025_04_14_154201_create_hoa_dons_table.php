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
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hop_dong_id');
            $table->string('so_hoa_don')->unique();
            $table->date('ngay_phat_hanh');
            $table->decimal('so_tien', 15, 2);
            $table->string('trang_thai')->default('chưa thanh toán'); // Đã thanh toán, Quá hạn
            $table->timestamps();

            $table->foreign('hop_dong_id')->references('id')->on('hop_dongs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
