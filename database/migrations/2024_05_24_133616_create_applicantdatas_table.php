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
        Schema::create('applicantdatas', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->string('nama');
            $table->string('email');
            $table->string('password');
            $table->string('hp');
            $table->string('telp')->nullable();
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('gender');
            $table->string('status_pernikahan');
            $table->string('golongan_darah');
            $table->string('agama');
            $table->string('etnis');
            $table->string('ptkp')->nullable();
            $table->string('nama_contact_darurat');
            $table->string('nama_contact_darurat_2');
            $table->string('contact_darurat_1');
            $table->string('contact_darurat_2');
            $table->string('hubungan_1');
            $table->string('hubungan_2');
            $table->string('jenis_identitas');
            $table->string('no_identitas');
            $table->string('alamat_identitas');
            $table->string('alamat_tinggal_sekarang');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicantdatas');
    }
};
