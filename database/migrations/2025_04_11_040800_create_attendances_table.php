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
        Schema::create('attendances', function (Blueprint $table) { //Bnage chấm công
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('ngay');
            $table->unsignedTinyInteger('thang'); // Tháng
            $table->unsignedSmallInteger('nam');  // Năm
            $table->time('gio_vao')->nullable();
            $table->time('gio_ra')->nullable();
            $table->boolean('di_muon')->default(false);
            $table->boolean('ve_som')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'ngay']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
