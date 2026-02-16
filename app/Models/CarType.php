<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarType extends Model
{
    protected $fillable = ['name', 'car_brand_id'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }

    public function trims(): HasMany
    {
        return $this->hasMany(CarTrim::class, 'car_type_id'); // 🔹 مفرد
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'car_type_id');
    }
}