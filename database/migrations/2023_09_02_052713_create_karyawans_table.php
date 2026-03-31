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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->uuid('id');
            // $table->foreignId('branch_id')->nullable();
            $table->integer('id_karyawan')->unique();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('hp')->nullable();
            $table->string('telepon')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('gender')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->string('agama')->nullable();

            $table->string('jenis_identitas')->nullable();
            $table->string('no_identitas')->nullable();
            $table->string('alamat_identitas')->nullable();
            $table->string('alamat_tinggal')->nullable();

            $table->string('status_karyawan')->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('placement_id')->nullable();
            $table->foreignId('department_id')->nullable();
            // $table->string('jabatan')->nullable();
            $table->foreignId('jabatan_id')->nullable();

            $table->string('level_jabatan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening')->nullable();


            $table->string('metode_penggajian')->nullable();
            $table->integer('gaji_pokok')->nullable();
            $table->integer('gaji_overtime')->nullable();
            $table->integer('bonus')->nullable();
            $table->integer('tunjangan_jabatan')->nullable();
            $table->integer('tunjangan_bahasa')->nullable();
            $table->integer('tunjangan_skill')->nullable();
            $table->integer('tunjangan_lembur_sabtu')->nullable();
            $table->integer('tunjangan_lama_kerja')->nullable();

            $table->integer('iuran_air')->nullable();
            $table->integer('iuran_locker')->nullable();
            $table->integer('gaji_bpjs')->nullable();
            $table->boolean('potongan_JHT')->nullable();
            $table->boolean('potongan_JP')->nullable();
            $table->boolean('potongan_JKK')->nullable();
            $table->boolean('potongan_JKM')->nullable();
            $table->boolean('potongan_kesehatan')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('ptkp')->nullable();
            $table->integer('denda')->nullable();
            $table->integer('gaji_shift_malam_satpam')->nullable();
            $table->string('etnis')->nullable();
            $table->date('tanggal_resigned')->nullable();
            $table->date('tanggal_blacklist')->nullable();
            $table->string('kontak_darurat')->nullable();
            $table->string('kontak_darurat2')->nullable();
            $table->string('hp1')->nullable();
            $table->string('hp2')->nullable();
            $table->string('hubungan1')->nullable();
            $table->string('hubungan2')->nullable();
            $table->integer('tanggungan')->nullable();
            $table->integer('id_file_karyawan')->nullable();
            $table->date('tanggal_update')->nullable();




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
