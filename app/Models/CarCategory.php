<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CarCategory extends Model
{
      use HasFactory;

   
    protected $table = 'car_categories';

    protected $fillable = [
        'name',
    ];

   
    protected $casts = [
        'id' => 'integer',
    ];

 
    public function cars()
    {
        return $this->hasMany(Car::class, 'car_category_id');
    }

   
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }
}
