<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FuelTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $fuelTypes = [
            ['name' => 'بنزين','created_at' => now(), 'updated_at' => now()],
            ['name' => 'ديزل','created_at' => now(), 'updated_at' => now()],
            ['name' => 'هايبرد','created_at' => now(), 'updated_at' => now()],
            ['name' => 'كهربائي','created_at' => now(), 'updated_at' => now()],
            ['name' => 'غاز طبيعي','created_at' => now(), 'updated_at' => now()],
            ['name' => 'بنزين + غاز','created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('fuel_types')->insert($fuelTypes);
    }
}
