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
        Schema::create('timeoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('karyawan_id');
            $table->string('department_id');
            $table->string('request_type');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('description');
            $table->string('status')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('approve1')->nullable();
            $table->date('approve1_date')->nullable();
            $table->string('approve2')->nullable();
            $table->date('approve2_date')->nullable();
            $table->string('done_by')->nullable();
            $table->date('done_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeoffs');
    }
};
