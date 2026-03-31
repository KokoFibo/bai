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
        Schema::create('yfrekappresensis', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('karyawan_id');
            $table->integer('user_id');
            $table->index(['user_id']);
            $table->index(['karyawan_id']);

            // $table->string('name');
            // $table->string('department');
            // $table->string('jabatan');
            $table->date('date');
            $table->time('first_in')->nullable();
            $table->time('first_out')->nullable();
            $table->time('second_in')->nullable();
            $table->time('second_out')->nullable();
            $table->time('overtime_in')->nullable();
            $table->time('overtime_out')->nullable();
            $table->decimal('total_jam_kerja', 6, 1)->nullable();
            $table->decimal('total_hari_kerja', 5, 1)->nullable();
            $table->decimal('total_jam_lembur', 6, 1)->nullable();
            $table->decimal('total_jam_kerja_libur', 6, 1)->nullable();

            $table->integer('late')->nullable();
            $table->string('no_scan')->nullable();
            $table->string('shift')->nullable();
            $table->string('no_scan_history')->nullable();
            $table->string('late_history')->nullable();
            $table->integer('shift_malam')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yfrekappresensis');
    }
};
