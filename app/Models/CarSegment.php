<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarSegment extends Model
{
    protected $fillable = ['segment', 'description'];
    
      public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    
    public function insuranceRates()
    {
        return $this->hasMany(InsuranceRate::class);
    }
}
