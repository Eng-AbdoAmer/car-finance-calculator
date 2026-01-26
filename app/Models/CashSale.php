<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'phone', // تأكد أن هذا الحقل موجود في جدول قاعدة البيانات
        'user_id',
        'car_brand_id',
        'car_model_id',
        'bank_id',
        'car_color',
        'car_category',
        'car_price',
        'paid_amount',
        'remaining_amount',
        'source',
        'payment_status',
        'notes',
        'payments',
        'is_active'
    ];

    protected $casts = [
        'car_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'payments' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع ماركة السيارة
    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    // علاقة مع موديل السيارة
    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    // علاقة مع البنك
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // حاسبة المبلغ المتبقي
    public static function calculateRemaining($price, $paid)
    {
        return max(0, $price - $paid);
    }

    // تحقق من اكتمال الدفع
    public function isFullyPaid()
    {
        return $this->remaining_amount <= 0;
    }

    // تحديث حالة الدفع تلقائياً
    public function updatePaymentStatus()
    {
        if ($this->remaining_amount <= 0) {
            $this->payment_status = 'fully_paid';
        } elseif ($this->paid_amount > 0 && $this->remaining_amount > 0) {
            $this->payment_status = 'partial_paid';
        } else {
            $this->payment_status = 'pending';
        }
        
        $this->save();
    }

    // إضافة دفعة جديدة (مبسطة بدون payment_method)
 // في App\Models\CashSale.php

// إصلاح دالة addSimplePayment
public function addSimplePayment($amount, $date, $method = 'نقدي', $notes = null)
{
    // تحويل payments الحالية إلى مصفوفة
    $payments = $this->payments ?? [];
    
    // إنشاء الدفعة الجديدة
    $newPayment = [
        'id' => count($payments) + 1,
        'amount' => $amount,
        'date' => $date,
        'method' => $method,
        'notes' => $notes,
        'created_at' => now()->toDateTimeString()
    ];
    
    // إضافة الدفعة الجديدة إلى المصفوفة
    $payments[] = $newPayment;
    
    // تحديث المبالغ
    $this->paid_amount += $amount;
    $this->remaining_amount = max(0, $this->car_price - $this->paid_amount);
    
    // تحديث حالة الدفع
    if ($this->remaining_amount <= 0) {
        $this->payment_status = 'fully_paid';
    } elseif ($this->paid_amount > 0 && $this->remaining_amount > 0) {
        $this->payment_status = 'partial_paid';
    } else {
        $this->payment_status = 'pending';
    }
    
    // حفظ الدفعات كـ JSON
    $this->payments = $payments;
    
    // حفظ التغييرات
    $this->save();
    
    return $newPayment;
}

// أو استخدم هذه الدالة المبسطة
public function addPayment($amount, $date, $method = 'نقدي', $notes = null)
{
    // تحديث المبالغ مباشرة
    $this->paid_amount += $amount;
    $this->remaining_amount = max(0, $this->car_price - $this->paid_amount);
    
    // تحديث حالة الدفع
    $this->updatePaymentStatus();
    
    // الحصول على الدفعات الحالية
    $payments = $this->payments;
    if (!is_array($payments)) {
        $payments = [];
    }
    
    // إضافة الدفعة الجديدة
    $payments[] = [
        'id' => count($payments) + 1,
        'amount' => $amount,
        'date' => $date,
        'method' => $method,
        'notes' => $notes,
        'created_at' => now()->toDateTimeString()
    ];
    
    // حفظ الدفعات
    $this->payments = $payments;
    
    // حفظ التغييرات
    return $this->save();
}

    // الحصول على سجل الدفعات
// تأكد أن هذه الدالة موجودة وتعامل مع جميع الحالات
public function getPaymentsAttribute($value)
{
    // إذا كانت القيمة null أو فارغة
    if (is_null($value) || $value === '' || $value === '[]') {
        return [];
    }
    
    // إذا كانت القيمة مصفوفة بالفعل
    if (is_array($value)) {
        return $value;
    }
    
    // إذا كانت نص JSON
    if (is_string($value) && str_starts_with($value, '[')) {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
    
    // في حالة كان نصاً عادياً (غير JSON)
    return [];
}

    // خاصية محسوبة للسيارة
    public function getCarInfoAttribute()
    {
        $brand = $this->carBrand ? $this->carBrand->name : 'غير معروف';
        $model = $this->carModel ? $this->carModel->name : 'غير معروف';
        return $brand . ' - ' . $model;
    }

    // سكوب للمبيعات النشطة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // سكوب للمبيعات حسب الحالة
    public function scopeStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    // سكوب للمبيعات حسب المصدر
    public function scopeSource($query, $source)
    {
        return $query->where('source', $source);
    }
}