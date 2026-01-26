<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarBrand;


class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $brands = [
            'تويوتا',
            'لكزس',
            'نيسان',
            'هوندا',
            'هيونداي',
            'كيا',
            'مرسيدس',
            'بي ام دبليو'
        ];

        foreach ($brands as $brand) {
            CarBrand::updateOrCreate([
                'name' => $brand
            ]);
        }
    }
}
