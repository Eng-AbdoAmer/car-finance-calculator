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
        Schema::create('age_brackets', function (Blueprint $table) {
            $table->id();
             $table->string('name'); // '18 to 24', '25 to 30', etc.
             $table->string('slug')->unique(); // '18-24', '25-30', etc.
             $table->integer('min_age')->nullable();
             $table->integer('max_age')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('age_brackets');
    }
};
