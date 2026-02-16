<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CarStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $statuses = [
            ['name' => 'متاحة للبيع', 'color' => '#10B981', 'order' => 1,'created_at' => now(), 'updated_at' => now()],
            ['name' => 'محجوزة', 'color' => '#F59E0B', 'order' => 2,'created_at' => now(), 'updated_at' => now()],
            ['name' => 'مباعة', 'color' => '#3B82F6', 'order' => 3,'created_at' => now(), 'updated_at' => now()],
            ['name' => 'قيد الصيانة', 'color' => '#EF4444', 'order' => 4,'created_at' => now(), 'updated_at' => now()],
            ['name' => 'غير متاحة', 'color' => '#6B7280', 'order' => 5,'created_at' => now(), 'updated_at' => now()],
            ['name' => 'قيد الفحص', 'color' => '#8B5CF6', 'order' => 6,'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('car_statuses')->insert($statuses);
    }
}
