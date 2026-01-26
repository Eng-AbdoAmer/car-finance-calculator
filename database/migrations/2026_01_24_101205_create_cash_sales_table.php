<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
             $table->foreignId('car_brand_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_model_id')->constrained('car_models')->cascadeOnDelete(); // نبقى هذا فقط
            $table->string('car_color');
            $table->string('car_category'); 
            $table->decimal('car_price', 15, 2);
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->enum('source', ['in_stock', 'external_purchase']);
            $table->enum('payment_status', [
                'pending',          // قيد الانتظار
                'partial_paid',     // مدفوع جزئياً
                'fully_paid',       // مدفوع بالكامل
                'delivered',        // تم التسليم
                'cancelled',        // ملغي
                'refunded',         // تم الاسترجاع
                'on_hold'          // معلق
            ])->default('pending');
            $table->text('notes')->nullable();
             $table->json('payments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // فهارس للأداء
            $table->index('payment_status');
            $table->index('source');
            $table->index(['user_id', 'created_at']);
                });
    }

    public function down()
    {
        Schema::dropIfExists('cash_sales');
    }
};