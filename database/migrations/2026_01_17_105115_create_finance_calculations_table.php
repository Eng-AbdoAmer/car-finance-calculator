<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {   //حسبة بنك الراجحي
        Schema::create('finance_calculations', function (Blueprint $table) {
            $table->id();
            
            // معلومات السيارة والتأمين
            $table->decimal('car_price', 15, 2);
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'sold', 'not_sold', 'follow_up', 'cancelled'])->default('pending');
            $table->foreignId('car_model_id')->constrained('car_models')->cascadeOnDelete();
            $table->decimal('down_payment_percentage', 5, 2)->default(0);
            $table->decimal('down_payment_amount', 15, 2);
            $table->decimal('financed_amount', 15, 2);
            $table->integer('loan_term_months');
            $table->decimal('profit_margin_percentage', 5, 2);
            $table->decimal('administrative_fees_percentage', 5, 2);
            $table->decimal('administrative_fees_amount', 15, 2);
            $table->decimal('insurance_rate_percentage', 5, 2);
            $table->decimal('final_payment_percentage', 5, 2);
            $table->decimal('final_payment_amount', 15, 2);
            
            // حساب القسط
            $table->decimal('monthly_installment_without_insurance', 15, 2);
            $table->decimal('monthly_installment_with_insurance', 15, 2);
            $table->decimal('flat_profit_rate_percentage', 5, 2);
            $table->decimal('total_cost_percentage', 5, 2);
            
            // معلومات العميل للتأمين
            $table->enum('gender', ['male', 'female']);
            $table->string('phone')->nullable();
            $table->string('age_bracket');
            $table->string('car_brand');
            $table->string('car_segment')->nullable();
            
            // إجماليات
            $table->decimal('total_fees', 15, 2);
            $table->decimal('total_profit', 15, 2);
            $table->decimal('total_insurance', 15, 2);
            $table->decimal('remaining_car_value', 15, 2);
            $table->decimal('grand_total', 15, 2);
            $table->foreignId('insurance_rate_id')->nullable()->constrained('insurance_rates');
            $table->decimal('ftp_percentage', 5, 2)->nullable();
            $table->decimal('cor_percentage', 5, 2)->nullable();
            $table->decimal('opex_percentage', 5, 2)->nullable();
            $table->decimal('breakeven_percentage', 5, 2)->nullable();
            $table->decimal('margin_percentage', 5, 2)->nullable();
            $table->decimal('net_profit', 15, 2)->nullable();
            $table->decimal('insurance_cost_arb', 5, 2)->nullable();
            $table->decimal('min_insurance_premium', 15, 2)->nullable();
            $table->decimal('rebate_percentage', 5, 2)->nullable();
            $table->decimal('rebate_amount', 15, 2)->nullable();
            $table->decimal('irr_percentage', 8, 4)->nullable();
            $table->decimal('profit_economic', 15, 2)->nullable();
            $table->decimal('max_administrative_fees', 15, 2)->nullable();
            $table->decimal('apr_percentage', 8, 5)->nullable();
            $table->decimal('total_insurance_amount', 15, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_calculations');
    }
};