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
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khach_hang_id');
            $table->string('ten_pipeline');
            $table->string('giai_doan')->nullable(); // VD: Tiếp cận, Đàm phán, Chốt sales
            $table->decimal('gia_tri_uoc_tinh', 15, 2)->nullable();
            $table->date('ngay_du_kien_thanh_toan')->nullable();
            $table->string('trang_thai')->default('đang theo dõi'); // Hoặc: đã chốt, huỷ
            $table->timestamps();

            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipelines');
    }
};
