<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgeBracket;

class AgeBracketSeeder extends Seeder
{
    public function run(): void
    {
        $ageBrackets = [
            ['name' => '18 to 24', 'slug' => '18-24'],
            ['name' => '25 to 30', 'slug' => '25-30'],
            ['name' => '31 to 35', 'slug' => '31-35'],
            ['name' => '36 to 40', 'slug' => '36-40'],
            ['name' => '41 to 50', 'slug' => '41-50'],
            ['name' => '51 to 60', 'slug' => '51-60'],
            ['name' => '61 & above', 'slug' => '61-above'],
        ];

        foreach ($ageBrackets as $bracket) {
            AgeBracket::create($bracket);
        }
    }
}