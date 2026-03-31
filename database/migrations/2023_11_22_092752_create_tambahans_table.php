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
        Schema::create('tambahans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('uang_makan')->nullable();
            $table->integer('bonus_lain')->nullable();
            $table->integer('baju_esd')->nullable();
            $table->integer('gelas')->nullable();
            $table->integer('sandal')->nullable();
            $table->integer('seragam')->nullable();
            $table->integer('sport_bra')->nullable();
            $table->integer('hijab_instan')->nullable();
            $table->integer('id_card_hilang')->nullable();
            $table->integer('masker_hijau')->nullable();
            $table->integer('potongan_lain')->nullable();
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tambahans');
    }
};
