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
        Schema::create('thanh_toans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hoa_don_id')->nullable(); // Thanh toán theo hóa đơn
            $table->integer('so_tien')->nullable(); // Số tiền thanh toán
            $table->date('ngay_thanh_toan');
            $table->string('phuong_thuc')->nullable(); // Chuyển khoản, tiền mặt...
            $table->string('trang_thai')->default('đã thanh toán'); // Hoặc: đang xử lý, hoàn tiền
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('hoa_don_id')->references('id')->on('hoa_dons')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thanh_toans');
    }
};
