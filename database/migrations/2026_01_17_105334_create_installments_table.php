<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_calculation_id')->constrained('finance_calculations')->onDelete('cascade');
            $table->integer('installment_number');
            $table->integer('year');
            $table->decimal('outstanding_balance', 15, 2);
            $table->decimal('profit_amount', 15, 2);
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('insurance_amount', 15, 2);
            $table->decimal('total_installment', 15, 2);
            
            // الحقول الجديدة
            $table->decimal('outstanding_plus_insurance', 15, 2)->nullable();
            $table->decimal('principal_plus_insurance', 15, 2)->nullable();
            $table->decimal('cash_flow', 15, 2)->nullable();
            $table->decimal('cf_percentage', 8, 2)->nullable();
            $table->decimal('ftp_monthly', 15, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};