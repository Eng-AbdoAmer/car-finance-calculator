<?php
// app/Models/Car.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'chassis_number', 'plate_number',
        'car_brand_id', 'car_type_id', 'car_trim_id', 'car_category_id',
        'car_status_id', 'transmission_type_id', 'fuel_type_id', 'drive_type_id',
        'model_year', 'color', 'condition', 'mileage',
        'engine_capacity', 'horse_power', 'cylinders', 'doors', 'seats',
        'purchase_price', 'selling_price', 'sold_price',
        'description', 'purchase_date', 'sale_date',
        'availability', 'view_count', 'inquiry_count',
        'sold_by', 'reserved_by',   // الحقول الجديدة
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'sale_date'     => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'sold_price'     => 'decimal:2',
        'model_year'     => 'integer',
        'mileage'        => 'integer',
    ];

    protected $appends = [
        'condition_name', 'availability_name', 'profit', 'profit_percentage',
        'days_in_stock', 'full_name'
    ];

    // ============ العلاقات ============
    public function brand() {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function type() {   // بدلاً من model()
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function trim() {
        return $this->belongsTo(CarTrim::class, 'car_trim_id');
    }

    public function category() {
        return $this->belongsTo(CarCategory::class, 'car_category_id');
    }

    public function status() {
        return $this->belongsTo(CarStatus::class, 'car_status_id');
    }

    public function transmission() {
        return $this->belongsTo(TransmissionType::class, 'transmission_type_id');
    }

    public function fuelType() {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    public function driveType() {
        return $this->belongsTo(DriveType::class, 'drive_type_id');
    }

    public function images() {
        return $this->hasMany(CarImage::class);
    }

    public function mainImage() {
        return $this->hasOne(CarImage::class)->where('is_main', true);
    }

    public function priceHistory() {
        return $this->hasMany(CarPriceHistory::class);
    }

    public function maintenanceRecords() {
        return $this->hasMany(CarMaintenanceRecord::class);
    }

    // العلاقات مع المستخدمين
    public function soldBy() {
        return $this->belongsTo(User::class, 'sold_by');
    }

    public function reservedBy() {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    // ============ النطاقات ============
    public function scopeAvailable($query) {
        return $query->where('availability', 'available');
    }

    public function scopeSold($query) {
        return $query->where('availability', 'sold');
    }

    public function scopeFeatured($query) {
        return $query->where('is_featured', true);
    }

    // ============ Accessors ============
    public function getConditionNameAttribute() {
        $map = ['new' => 'جديدة', 'used' => 'مستعملة', 'salvage' => 'تشليح', 'refurbished' => 'مجددة'];
        return $map[$this->condition] ?? $this->condition;
    }

    public function getAvailabilityNameAttribute() {
        $map = ['available' => 'متاحة', 'reserved' => 'محجوزة', 'sold' => 'مباعة', 'under_processing' => 'قيد المعالجة'];
        return $map[$this->availability] ?? $this->availability;
    }

    public function getProfitAttribute() {
        if ($this->sold_price && $this->purchase_price) {
            return $this->sold_price - $this->purchase_price;
        }
        if ($this->selling_price && $this->purchase_price) {
            return $this->selling_price - $this->purchase_price;
        }
        return 0;
    }

    public function getProfitPercentageAttribute() {
        return $this->purchase_price > 0 ? round(($this->profit / $this->purchase_price) * 100, 2) : 0;
    }

    public function getDaysInStockAttribute() {
        if ($this->created_at) {
            $end = $this->sale_date ? Carbon::parse($this->sale_date) : Carbon::now();
            return $this->created_at->diffInDays($end);
        }
        return null;
    }

    public function getFullNameAttribute() {
        $parts = [];
        if ($this->brand) $parts[] = $this->brand->name;
        if ($this->type) $parts[] = $this->type->name;
        if ($this->model_year) $parts[] = $this->model_year;
        if ($this->trim) $parts[] = $this->trim->name;
        return implode(' ', $parts);
    }

    // ============ توليد الكود التلقائي ============
    public static function generateCode()
    {
        $year   = date('Y');
        $month  = date('m');
        $prefix = 'CAR-' . $year . $month . '-';

        return DB::transaction(function () use ($prefix) {
            $lastCar = self::where('code', 'like', $prefix . '%')
                          ->orderBy('code', 'desc')
                          ->lockForUpdate()
                          ->first();
            if ($lastCar) {
                $lastNumber = (int) substr($lastCar->code, strlen($prefix));
                $newNumber  = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }
            return $prefix . $newNumber;
        });
    }
}