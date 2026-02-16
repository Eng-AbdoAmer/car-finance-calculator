<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FuelType extends Model
{
     use HasFactory;


    protected $table = 'fuel_types';

    protected $fillable = [
        'name',
    ];

   
    protected $casts = [
        'id' => 'integer',
    ];

    
    public function cars()
    {
        return $this->hasMany(Car::class, 'fuel_type_id');
    }

    
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }
}
