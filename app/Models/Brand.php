<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
     protected $fillable = ['name', 'slug', 'car_segment_id'];
    
    public function carSegment()
    {
        return $this->belongsTo(CarSegment::class);
    }
}
