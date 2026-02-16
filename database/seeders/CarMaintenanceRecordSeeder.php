<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarMaintenanceRecord;
use App\Models\Car;
use App\Models\User;

class CarMaintenanceRecordSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // افتراض مستخدم موجود
        $cars = Car::take(5)->get(); // بعض السيارات للتمثيل

        foreach ($cars as $car) {
            CarMaintenanceRecord::create([
                'car_id'            => $car->id,
                'maintenance_date'  => now()->subMonths(2),
                'maintenance_type'  => 'Oil Change',
                'description'       => 'Changed engine oil and filter',
                'cost'              => 120.00,
                'service_center'    => 'QuickFix Garage',
                'details'           => ['oil_type' => '5W-30', 'filter_model' => 'XYZ123'],
                'recorded_by'       => $user->id,
                'created_at' => now(), 'updated_at' => now()
            ]);

            CarMaintenanceRecord::create([
                'car_id'            => $car->id,
                'maintenance_date'  => now()->subMonth(),
                'maintenance_type'  => 'Tire Service',
                'description'       => 'Replaced all 4 tires',
                'cost'              => 800.00,
                'service_center'    => 'TireWorld',
                'details'           => ['tire_brand' => 'Michelin', 'size' => '225/50R17'],
                'recorded_by'       => $user->id,
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }
}
