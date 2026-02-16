<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CarMaintenanceRecord extends Model
{
      use HasFactory;
        protected $table = 'car_maintenance_records';

 
    protected $fillable = [
        'car_id',
        'maintenance_date',
        'maintenance_type',
        'description',
        'cost',
        'service_center',
        'details',
        'recorded_by',
    ];

 
    protected $casts = [
        'car_id'            => 'integer',
        'maintenance_date'  => 'date',
        'cost'              => 'decimal:2',
        'details'           => 'array',
        'recorded_by'       => 'integer',
    ];

 

    // سجل الصيانة مرتبط بسيارة
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // المستخدم الذي سجل الصيانة
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /* ===========================
     |          Scopes
     =========================== */
    public function scopeForCar($query, int $carId)
    {
        return $query->where('car_id', $carId);
    }


    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('maintenance_date');
    }


    public function scopeByType($query, string $type)
    {
        return $query->where('maintenance_type', $type);
    }
}
