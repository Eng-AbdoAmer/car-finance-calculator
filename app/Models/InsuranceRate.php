<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRate extends Model
{
    protected $fillable = ['gender', 'age_bracket_id', 'car_segment_id', 'rate'];
    protected $casts = [
        'rate' => 'decimal:2',
    ];
    
    public function ageBracket()
    {
        return $this->belongsTo(AgeBracket::class);
    }
    
    public function carSegment()
    {
        return $this->belongsTo(CarSegment::class);
    }
}
