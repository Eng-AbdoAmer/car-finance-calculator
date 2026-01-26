<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
       protected $fillable = [
        'name',
    ];

    public function financingRequests()
    {
        return $this->hasMany(CarFinancingRequest::class);
    }
}
