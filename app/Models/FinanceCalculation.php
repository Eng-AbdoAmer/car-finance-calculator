<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_price',
        'user_id',
        'status',
        'car_model_id',
        'down_payment_percentage',
        'down_payment_amount',
        'financed_amount',
        'loan_term_months',
        'profit_margin_percentage',
        'administrative_fees_percentage',
        'administrative_fees_amount',
        'insurance_rate_percentage',
        'final_payment_percentage',
        'final_payment_amount',
        'monthly_installment_without_insurance',
        'monthly_installment_with_insurance',
        'flat_profit_rate_percentage',
        'total_cost_percentage',
        'gender',
        'phone',
        'age_bracket',
        'car_brand',
        'car_segment',
        'total_fees',
        'total_profit',
        'total_insurance',
        'remaining_car_value',
        'grand_total',
        'insurance_rate_id',
        
        // الحقول الجديدة
        'ftp_percentage',
        'cor_percentage',
        'opex_percentage',
        'breakeven_percentage',
        'margin_percentage',
        'net_profit',
        'insurance_cost_arb',
        'min_insurance_premium',
        'rebate_percentage',
        'rebate_amount',
        'irr_percentage',
        'profit_economic',
        'max_administrative_fees',
        'apr_percentage',
        'total_insurance_amount',
    ];

    protected $casts = [
        'car_price' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'financed_amount' => 'decimal:2',
        'administrative_fees_amount' => 'decimal:2',
        'final_payment_amount' => 'decimal:2',
        'monthly_installment_without_insurance' => 'decimal:2',
        'monthly_installment_with_insurance' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'total_insurance' => 'decimal:2',
        'remaining_car_value' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'min_insurance_premium' => 'decimal:2',
        'rebate_amount' => 'decimal:2',
        'profit_economic' => 'decimal:2',
        'max_administrative_fees' => 'decimal:2',
        'total_insurance_amount' => 'decimal:2',
    ];
     public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }
    public function insuranceRate()
    {
        return $this->belongsTo(InsuranceRate::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
    /**
     * الحصول على رقم الهاتف بصيغة دولية
     */
    public function getInternationalPhoneAttribute()
    {
        $phone = $this->phone;
        
        if (!$phone) {
            return null;
        }
        
        // إزالة أي رموز غير رقمية
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // معالجة الرقم ليكون بصيغة دولية
        if (substr($cleanPhone, 0, 3) === '966' && strlen($cleanPhone) >= 9) {
            return $cleanPhone;
        } elseif (substr($cleanPhone, 0, 1) === '0' && strlen($cleanPhone) === 10) {
            return '966' . substr($cleanPhone, 1);
        } elseif (strlen($cleanPhone) === 9) {
            return '966' . $cleanPhone;
        }
        
        return $cleanPhone;
    }

    // في FinanceCalculation.php
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