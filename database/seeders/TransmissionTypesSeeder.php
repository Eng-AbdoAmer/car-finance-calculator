<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TransmissionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('transmission_types')->insert([
            ['name' => 'أوتوماتيك','created_at' => now(), 'updated_at' => now()],
            ['name' => 'عادي','created_at' => now(), 'updated_at' => now()],
            ['name' => 'أوتوماتيك CVT','created_at' => now(), 'updated_at' => now()],
            ['name' => 'أوتوماتيك DCT','created_at' => now(), 'updated_at' => now()],
            ['name' => 'نصف أوتوماتيك','created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
