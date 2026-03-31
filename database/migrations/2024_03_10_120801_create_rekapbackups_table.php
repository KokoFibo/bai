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
        Schema::create('rekapbackups', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('karyawan_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->date('date')->nullable();
            $table->time('first_in')->nullable();
            $table->time('first_out')->nullable();
            $table->time('second_in')->nullable();
            $table->time('second_out')->nullable();
            $table->time('overtime_in')->nullable();
            $table->time('overtime_out')->nullable();
            $table->integer('late')->nullable();
            $table->string('no_scan')->nullable();
            $table->string('shift')->nullable();
            $table->string('no_scan_history')->nullable();
            $table->string('late_history')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekapbackups');
    }
};
