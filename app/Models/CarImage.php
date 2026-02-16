<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarImage extends Model
{
    use HasFactory;
    
    protected $table = 'car_images';

    protected $fillable = [
        'car_id',
        'image_path',
        'image_type',
        'is_main',
        'order',
        'description',
    ];

    protected $casts = [
        'car_id'  => 'integer',
        'is_main' => 'boolean',
        'order'   => 'integer',
    ];

    // العلاقة مع السيارة
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    /* ===========================
     |          Scopes
     =========================== */

    // الصورة الرئيسية فقط
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    // حسب نوع الصورة (خارجي / داخلي)
    public function scopeByType($query, string $type)
    {
        return $query->where('image_type', $type);
    }

    // مرتبة
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // الصور الخاصة بسيارة معينة
    public function scopeForCar($query, $carId)
    {
        return $query->where('car_id', $carId);
    }

    /* ===========================
     |       Accessors
     =========================== */

    // رابط الصورة الكامل
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    // رابط الصورة المصغرة
    public function getThumbnailUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    // نوع الصورة بالعربية
    public function getTypeArabicAttribute()
    {
        $types = [
            'exterior' => 'خارجي',
            'interior' => 'داخلي',
            'engine' => 'محرك',
            'document' => 'مستند',
        ];
        
        return $types[$this->image_type] ?? $this->image_type;
    }

    // حجم الملف (إذا كان موجوداً)
    public function getFileSizeAttribute()
    {
        $path = storage_path('app/public/' . $this->image_path);
        
        if (file_exists($path)) {
            $size = filesize($path);
            
            if ($size >= 1048576) {
                return round($size / 1048576, 2) . ' MB';
            } elseif ($size >= 1024) {
                return round($size / 1024, 2) . ' KB';
            } else {
                return $size . ' بايت';
            }
        }
        
        return 'غير متاح';
    }
}