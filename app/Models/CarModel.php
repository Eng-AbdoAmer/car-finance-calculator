<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarModel extends Model
{
    

    protected $table = 'car_models';
   protected $fillable = ['model_year'];
   
   public function financingRequests()
    {
        return $this->hasMany(CarFinancingRequest::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'car_model_id');
    }

     public function trims(): HasMany
    {
        return $this->hasMany(CarTrim::class, 'car_type_id');
    }

     public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }
}
