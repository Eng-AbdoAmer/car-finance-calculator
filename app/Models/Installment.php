<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'finance_calculation_id',
        'installment_number',
        'year',
        'outstanding_balance',
        'profit_amount',
        'principal_amount',
        'insurance_amount',
        'total_installment',
        
        // الحقول الجديدة
        'outstanding_plus_insurance',
        'principal_plus_insurance',
        'cash_flow',
        'cf_percentage',
        'ftp_monthly',
    ];

    protected $casts = [
        'outstanding_balance' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'total_installment' => 'decimal:2',
        'outstanding_plus_insurance' => 'decimal:2',
        'principal_plus_insurance' => 'decimal:2',
        'cash_flow' => 'decimal:2',
        'cf_percentage' => 'decimal:2',
        'ftp_monthly' => 'decimal:2',
    ];

    public function financeCalculation()
    {
        return $this->belongsTo(FinanceCalculation::class);
    }
}