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
        Schema::create('locks', function (Blueprint $table) {
            $table->id();
            $table->boolean('upload')->nullable()->default(false);
            $table->boolean('build')->nullable()->default(false);
            $table->boolean('payroll')->nullable()->default(false);
            $table->boolean('rebuild_done')->nullable()->default(false);
            $table->boolean('presensi')->nullable()->default(false);
            $table->boolean('build_payroll')->nullable()->default(false);
            $table->boolean('tambahan')->nullable()->default(false);
            $table->boolean('slip_gaji')->nullable()->default(false);
            $table->boolean('data')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locks');
    }
};
