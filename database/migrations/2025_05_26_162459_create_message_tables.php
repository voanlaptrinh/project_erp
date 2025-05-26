<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Bảng nhóm chat
        Schema::create('message_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Tên nhóm nếu là chat nhóm
            $table->boolean('is_group')->default(false); // true: nhóm, false: chat riêng
            $table->timestamps();
        });

        // Bảng người dùng trong nhóm
        Schema::create('message_group_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_group_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('message_group_id')
                ->references('id')->on('message_groups')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->unique(['message_group_id', 'user_id']); // Tránh trùng
        });

        // Bảng tin nhắn
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_group_id');
            $table->unsignedBigInteger('user_id'); // Người gửi
            $table->text('content')->nullable();
            $table->string('attachment')->nullable(); // Đường dẫn file hoặc JSON
            $table->timestamps();

            $table->foreign('message_group_id')
                ->references('id')->on('message_groups')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Bảng trạng thái đọc
        Schema::create('message_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('message_id')
                ->references('id')->on('messages')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->unique(['message_id', 'user_id']); // Tránh trùng
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_reads');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('message_group_users');
        Schema::dropIfExists('message_groups');
    }
};