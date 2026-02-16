<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CarStatus extends Model
{
     use HasFactory;
      protected $table = 'car_statuses';
       protected $guarded=[];
    // protected $fillable = [
    //     'name',
    //     'color',
    //     'order',
    // ];

    protected $casts = [
        'id'    => 'integer',
        'order' => 'integer',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class, 'car_status_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
