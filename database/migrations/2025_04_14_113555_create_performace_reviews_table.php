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
        Schema::create('performace_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('thang');
            $table->integer('nam');
            $table->integer('ngay_cong')->default(0);
            $table->integer('so_task_hoan_thanh')->default(0);
            $table->integer('di_muon')->default(0);
            $table->integer('ve_som')->default(0);
            $table->float('hieu_suat')->default(0); // %
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performace_reviews');
    }
};
