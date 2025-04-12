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
        Schema::create('thong_ke_cham_congs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('thang'); // dạng YYYY-MM
            $table->integer('nam');  // dạng YYYY-MM
            $table->integer('ngay_di_muon')->default(0);
            $table->integer('ngay_ve_som')->default(0);
            $table->integer('ngay_nghi')->default(0);
            $table->integer('ngay_du')->default(0);
            $table->integer('tong_cong')->default(0);
            

            $table->unique(['user_id', 'thang']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_ke_cham_congs');
    }
};
