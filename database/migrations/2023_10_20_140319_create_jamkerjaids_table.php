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
        Schema::create('jamkerjaids', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->index('user_id');
            $table->foreignUuid('karyawan_id');
            $table->index('karyawan_id');
            $table->date('date')->nullable();
            $table->double('jumlah_jam_kerja', 4, 1)->nullable();
            $table->double('jumlah_menit_lembur', 4, 1)->nullable();
            $table->double('jam_kerja_libur', 4, 1)->nullable();
            $table->double('jumlah_jam_terlambat', 4, 1)->nullable();
            $table->double('tambahan_jam_shift_malam', 4, 1)->nullable();
            $table->double('total_noscan', 4, 1)->nullable();
            $table->double('total_hari_kerja', 5, 1)->nullable();
            $table->date('last_data_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamkerjaids');
    }
};
