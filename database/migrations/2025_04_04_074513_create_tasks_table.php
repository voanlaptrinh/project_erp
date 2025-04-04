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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('sprint_id')->nullable()->constrained()->onDelete('set null');
            $table->string('tieu_de');
            $table->text('mo_ta')->nullable();
            $table->enum('do_uu_tien', ['Thấp', 'Trung bình', 'Cao'])->default('Trung bình');
            $table->enum('trang_thai', ['Mới', 'Đang thực hiện', 'Hoàn thành'])->default('Mới');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('han_hoan_thanh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
