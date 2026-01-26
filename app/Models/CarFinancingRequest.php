<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarFinancingRequest extends Model
{
    protected $fillable = [
        'status',
        'user_id',
        'phone',
        'bank_id',
        'car_brand_id',
        'car_model_id',
        'car_price',
        'down_payment_percentage',
        'down_payment',
        'financing_amount',
        'duration_months',
        'last_payment_rate',
        'last_payment',
        'insurance_rate',
        'insurance_total',
        'murabaha_rate',
        'murabaha_total',
        'monthly_installment',
        'interest_with_insurance',
        'car_value_after_down',
        'total_amount',
    ];

    protected $casts = [
        'car_price' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'financing_amount' => 'decimal:2',
        'last_payment' => 'decimal:2',
        'insurance_total' => 'decimal:2',
        'murabaha_total' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'interest_with_insurance' => 'decimal:2',
        'car_value_after_down' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function brand()
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor للحالة
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'قيد الانتظار',
            'sold' => 'تم البيع',
            'not_sold' => 'لم يتم البيع',
            'follow_up' => 'متابعة',
            'cancelled' => 'ملغي'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
}