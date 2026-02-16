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
        Schema::create('car_price_histories', function (Blueprint $table) {
            $table->id();
             $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->enum('price_type', ['purchase', 'selling', 'cost', 'minimum', 'sold', 'offer', 'discount']);
            $table->decimal('old_price', 15, 2)->nullable();
            $table->decimal('new_price', 15, 2);
            $table->text('reason')->nullable();
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['car_id', 'price_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_price_histories');
    }
};
