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
        Schema::create('hop_dongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khach_hang_id');
            $table->string('so_hop_dong')->unique();
            $table->date('ngay_ky')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->decimal('gia_tri', 15, 2)->nullable();
            $table->text('noi_dung')->nullable();
            $table->string('trang_thai')->default('đang hiệu lực'); // Hết hiệu lực, Hủy
            $table->timestamps();

            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hop_dongs');
    }
};
