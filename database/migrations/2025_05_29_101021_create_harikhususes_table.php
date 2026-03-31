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
        Schema::create('harikhususes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('is_sunday')->nullable()->default(false);
            $table->boolean('is_friday')->nullable()->default(false);
            $table->boolean('is_saturday')->nullable()->default(false);
            $table->boolean('is_hari_libur_nasional')->nullable()->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harikhususes');
    }
};
