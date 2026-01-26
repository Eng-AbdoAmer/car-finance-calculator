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
        Schema::create('insurance_rates', function (Blueprint $table) {
            $table->id();
             $table->enum('gender', ['male', 'female']);
              $table->foreignId('age_bracket_id')->constrained('age_brackets');
            $table->foreignId('car_segment_id')->constrained('car_segments');
            $table->decimal('rate', 5, 2); // 8.40%
            $table->timestamps();
            $table->unique(['gender', 'age_bracket_id', 'car_segment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_rates');
    }
};
