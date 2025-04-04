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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->enum('muc_do', ['Nhẹ', 'Trung bình', 'Nghiêm trọng'])->default('Trung bình');
            $table->enum('trang_thai', ['Mới', 'Đang xử lý', 'Đã giải quyết'])->default('Mới');
            $table->foreignId('nguoi_bao_cao')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
