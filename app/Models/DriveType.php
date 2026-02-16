<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriveType extends Model
{
        use HasFactory;
      protected $table = 'drive_types';
     protected $guarded=[];
    // protected $fillable = [
    //     'name',
    //     'code',
    // ];

    protected $casts = [
        'id' => 'integer',
    ];

   
    public function cars()
    {
        return $this->hasMany(Car::class, 'drive_type_id');
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }
}
