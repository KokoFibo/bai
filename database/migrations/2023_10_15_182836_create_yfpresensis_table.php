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
        Schema::create('yfpresensis', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            // $table->string("name");
            // $table->string("department");
            $table->date("date");
            $table->time("time");
            $table->integer("day_number");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yfpresensis');
    }
};
