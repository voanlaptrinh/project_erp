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
        Schema::create('hop_dongs', function (Blueprint $table) { //gợp đồng dự án với khách hàng
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('so_hop_dong')->nullable();
            $table->string('alias')->nullable();
            $table->string('file')->nullable(); //hợp đồng tay khi up lên
            $table->date('ngay_ky')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->decimal('gia_tri', 15, 2)->nullable();
            $table->text('noi_dung')->nullable();
            $table->string('trang_thai')->default('đang hiệu lực'); // Hết hiệu lực, Hủy
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
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
