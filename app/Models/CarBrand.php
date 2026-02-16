<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarBrand extends Model
{
    protected $fillable = ['name'];

    public function types(): HasMany
    {
        return $this->hasMany(CarType::class, 'car_brand_id');
    }
     public function trims(): HasMany
    {
        return $this->hasMany(CarTrim::class, 'car_brand_id');
    }
     public function cars()
    {
        return $this->hasMany(Car::class, 'car_brand_id');
    }
}