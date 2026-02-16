<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CarPriceHistory extends Model
{
      use HasFactory;
      protected $table = 'car_price_histories';

  
    protected $fillable = [
        'car_id',
        'price_type',
        'old_price',
        'new_price',
        'reason',
        'changed_by',
    ];


    protected $casts = [
        'car_id'     => 'integer',
        'changed_by'=> 'integer',
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
    ];

  

    // سجل السعر يتبع سيارة
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // المستخدم الذي غيّر السعر
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /* ===========================
     |          Scopes
     =========================== */
    public function scopeByPriceType($query, string $type)
    {
        return $query->where('price_type', $type);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }
}
