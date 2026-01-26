<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * البنوك المتعددة
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_financing_requests', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'sold', 'not_sold', 'follow_up', 'cancelled'])->default('pending');
            $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_brand_id')->constrained('car_brands')->cascadeOnDelete();
            $table->foreignId('car_model_id')->constrained('car_models')->cascadeOnDelete();
            $table->decimal('car_price', 12, 2);
            $table->decimal('down_payment_percentage', 5, 2);
            $table->decimal('down_payment', 12, 2);
            $table->decimal('financing_amount', 12, 2);
            $table->integer('duration_months');
             $table->decimal('last_payment_rate', 5, 2);
            $table->decimal('last_payment', 12, 2);
            $table->decimal('insurance_rate', 5, 2);
            $table->decimal('insurance_total', 12, 2);
            $table->decimal('murabaha_rate', 5, 2);
            $table->decimal('murabaha_total', 12, 2);
            $table->decimal('monthly_installment', 12, 2);
            $table->decimal('interest_with_insurance', 12, 2);
            $table->decimal('car_value_after_down', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_financing_requests');
    }
};
