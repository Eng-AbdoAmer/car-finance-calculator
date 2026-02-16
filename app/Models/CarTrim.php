<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class CarTrim extends Model
{
    protected $fillable = ['name', 'code', 'car_brand_id', 'car_type_id']; 

    protected $casts = [
        'id' => 'integer',
        'car_brand_id' => 'integer',
        'car_type_id' => 'integer', 
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function getFullNameAttribute(): string
    {
        $brandName = $this->brand ? $this->brand->name : '';
        $typeName = $this->type ? $this->type->name : '';
        $trimName = $this->name;
        
        return trim("{$brandName} {$typeName} {$trimName}");
    }

      public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_trim_id');
    }
}