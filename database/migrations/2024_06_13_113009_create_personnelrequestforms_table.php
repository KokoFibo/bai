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
        Schema::create('personnelrequestforms', function (Blueprint $table) {
            $table->id();
            $table->string('requester_id');
            $table->string('placement');
            $table->string('posisi')->nullable();
            $table->string('jumlah_dibutuhkan')->nullable();
            $table->string('level_posisi')->nullable();
            $table->string('manpower_posisi')->nullable();
            $table->string('jumlah_manpower_saat_ini')->nullable();
            $table->string('waktu_masuk_kerja')->nullable();
            $table->string('job_description')->nullable();
            $table->string('usia')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pengalaman_kerja')->nullable();
            $table->string('kualifikasi_lain')->nullable();
            $table->string('kisaran_gaji')->nullable();
            $table->string('gender')->nullable();
            $table->string('skil_wajib')->nullable();
            $table->string('alasan_permohonan')->nullable();
            $table->date('tgl_request');
            $table->string('approve_by_1')->nullable();
            $table->date('approve_date_1')->nullable();
            $table->string('approve_by_2')->nullable();
            $table->date('approve_date_2')->nullable();
            $table->string('done_by')->nullable();
            $table->date('done_date')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('personnelrequestforms');
    }
};
