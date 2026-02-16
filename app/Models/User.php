<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(storage_path('app/public/avatars/' . $this->avatar))) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        // صورة افتراضية
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
    public function financeCalculations()
    {
        return $this->hasMany(FinanceCalculation::class);
    }

    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function isUser()
    {
        return $this->type === 'user';
    }
       public function cashSales()
    {
        return $this->hasMany(CashSale::class);
    }
     public function carFinancingRequests()
    {
        return $this->hasMany(CarFinancingRequest::class);
    }
  public function soldCars()
    {
        return $this->hasMany(Car::class, 'sold_by');
    }

    public function reservedCars()
    {
        return $this->hasMany(Car::class, 'reserved_by');
    }
}