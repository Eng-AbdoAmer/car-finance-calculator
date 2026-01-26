<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarSegment; 

class CarSegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $segments = [
            ['segment' => 'A', 'description' => 'Segment A Cars'],
            ['segment' => 'B', 'description' => 'Segment B Cars'],
            ['segment' => 'C', 'description' => 'Segment C Cars'],
            ['segment' => 'D', 'description' => 'Segment D Cars'],
            ['segment' => 'E', 'description' => 'Luxury Segment E Cars'],
            ['segment' => 'F', 'description' => 'Motorbikes'],
            ['segment' => 'G', 'description' => 'Electric Cars'],
        ];

        foreach ($segments as $segment) {
            CarSegment::create($segment);
        }
    }
}
