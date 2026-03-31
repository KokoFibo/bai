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
        Schema::create('liburnasionals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_hari_libur')->nullable();
            $table->date('tanggal_mulai_hari_libur')->nullable();
            $table->date('tanggal_akhir_libur')->nullable();
            $table->integer('jumlah_hari_libur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liburnasionals');
    }
};
