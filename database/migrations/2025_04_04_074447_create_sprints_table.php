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
        Schema::create('sprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('ten_sprint');
            $table->text('mo_ta')->nullable();
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->enum('trang_thai', ['Chưa bắt đầu', 'Đang thực hiện', 'Đã hoàn thành'])->default('Chưa bắt đầu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};
