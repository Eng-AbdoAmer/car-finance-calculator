<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarPriceHistory;
use App\Models\Car;
use App\Models\User;
class CarPriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); 
        $cars = Car::take(5)->get(); 

        foreach ($cars as $car) {
            CarPriceHistory::create([
                'car_id'     => $car->id,
                'price_type' => 'selling',
                'old_price'  => $car->purchase_price,
                'new_price'  => $car->selling_price,
                'reason'     => 'Initial price set',
                'changed_by' => $user->id,
                'created_at' => now(), 'updated_at' => now()
            ]);

            // مثال لتغيير السعر مرة أخرى
            CarPriceHistory::create([
                'car_id'     => $car->id,
                'price_type' => 'selling',
                'old_price'  => $car->selling_price,
                'new_price'  => $car->selling_price + 2000,
                'reason'     => 'Price adjustment',
                'changed_by' => $user->id,
                'created_at' => now(), 'updated_at' => now()
            ]);
    }
}
}