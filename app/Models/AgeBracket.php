<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeBracket extends Model
{
    protected $fillable = ['name', 'slug'];
    
    public function insuranceRates()
    {
        return $this->hasMany(InsuranceRate::class);
    }
}
