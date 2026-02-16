<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
      $this->call([
        UserSeeder::class,
        CarSegmentSeeder::class,
        AgeBracketSeeder::class,
        BrandSeeder::class,
        InsuranceRateSeeder::class,
        BankSeeder::class,
        CarStatusesSeeder::class,
        DriveTypesSeeder::class,
        FuelTypesSeeder::class,
        TransmissionTypesSeeder::class,
        CarMaintenanceRecordSeeder::class,
        CarPriceHistorySeeder::class,
        CarCategoriesSeeder::class,
          CarBrandSeeder::class,
          CarTypeSeeder::class,
            // CarTypeSeeder::class, 
            CarModelSeeder::class,
           CarTrimSeeder::class,
        
        ]);
   
    }
}