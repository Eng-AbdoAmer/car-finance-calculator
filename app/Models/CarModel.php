<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = [
        'model_year'
    ];

   public function financingRequests()
    {
        return $this->hasMany(CarFinancingRequest::class);
    }

    
}
