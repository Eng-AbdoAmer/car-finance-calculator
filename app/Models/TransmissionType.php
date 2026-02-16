<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransmissionType extends Model
{
     use HasFactory;

    protected $table = 'transmission_types';

    
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

  
    public function cars()
    {
        return $this->hasMany(Car::class, 'transmission_type_id');
    }

   
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }
}
