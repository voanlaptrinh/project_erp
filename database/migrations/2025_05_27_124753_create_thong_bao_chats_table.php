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
        Schema::create('thong_bao_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Người nhận thông báo
            $table->string('title'); // Tiêu đề thông báo
            $table->text('message'); // Nội dung
            $table->boolean('is_read')->default(false); // Trạng thái đã đọc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao_chats');
    }
};
