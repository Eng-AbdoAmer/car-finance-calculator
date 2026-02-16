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
        Schema::create('cars', function (Blueprint $table) {
           $table->id();
            $table->string('code')->unique()->comment('كود السيارة الداخلي');
            $table->string('chassis_number')->unique()->nullable()->comment('رقم الهيكل');
            $table->string('plate_number')->unique()->nullable()->comment('رقم اللوحة');
            
            // العلاقات الأساسية
            $table->foreignId('car_brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_type_id')->constrained()->cascadeOnDelete();   // الموديل (كامري)
            $table->foreignId('car_trim_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('car_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('car_status_id')->constrained()->default(1);
            $table->foreignId('transmission_type_id')->constrained();
            $table->foreignId('fuel_type_id')->constrained();
            $table->foreignId('drive_type_id')->nullable()->constrained();
            
            // المستخدمين المسؤولين عن البيع والحجز
            $table->foreignId('sold_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reserved_by')->nullable()->constrained('users')->nullOnDelete();
            
            // المواصفات
            $table->year('model_year');                     // سنة الموديل
            $table->string('color');
            $table->enum('condition', ['new', 'used', 'salvage', 'refurbished'])->default('new');
            $table->integer('mileage')->default(0);
            $table->integer('engine_capacity')->nullable();
            $table->integer('horse_power')->nullable();
            $table->integer('cylinders')->default(4);
            $table->integer('doors')->default(4);
            $table->integer('seats')->default(5);
            
            // الأسعار
            $table->decimal('purchase_price', 15, 2)->comment('سعر الشراء');
            $table->decimal('selling_price', 15, 2)->comment('سعر البيع');
            $table->decimal('sold_price', 15, 2)->nullable()->comment('سعر البيع الفعلي');
            
            // معلومات إضافية
            $table->text('description')->nullable();
            
            // التواريخ
            $table->date('purchase_date')->nullable();
            $table->date('sale_date')->nullable();
            
            // الحالة (متاح، محجوز، مباع، تحت المعالجة)
            $table->enum('availability', ['available', 'reserved', 'sold', 'under_processing'])->default('available');
            
            // إحصائيات
            $table->integer('view_count')->default(0);
            $table->integer('inquiry_count')->default(0);
            
            $table->softDeletes();
            $table->timestamps();
            
            // فهارس
            $table->index(['car_brand_id', 'car_type_id']);
            $table->index('model_year');
            $table->index('condition');
            $table->index('selling_price');
            $table->index('availability');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
