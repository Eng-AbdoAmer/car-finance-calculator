<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * // 10. جدول سجل الصيانة
     */
    public function up(): void
    {
        Schema::create('car_maintenance_records', function (Blueprint $table) {
            $table->id();
           $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->date('maintenance_date');
            $table->string('maintenance_type');
            $table->text('description');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('service_center')->nullable();
            $table->json('details')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['car_id', 'maintenance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_maintenance_records');
    }
};
