<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarSegment; 
use App\Models\Brand; 
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $brands = [
            // Segment A
            ['Toyota', 'A'], ['Lexus', 'A'], ['Mazda', 'A'], ['Honda', 'A'], ['Nissan', 'A'],
            
            // Segment B
            ['Ford', 'B'], ['GM', 'B'], ['Chevrolet', 'B'], ['Isuzu', 'B'], ['Hyundai', 'B'],
            ['Kia', 'B'], ['Mitsubishi', 'B'],
            
            // Segment C
            ['Chrysler', 'C'], ['Dodge', 'C'], ['Jeep', 'C'], ['Suzuki', 'C'], ['Volkswagen', 'C'],
            ['Audi', 'C'], ['Mercedes', 'C'], ['BMW', 'C'], ['Porsche', 'C'], ['Range Rover', 'C'],
            ['Infiniti', 'C'], ['Fiat', 'C'],
            
            // Segment D
            ['Citroen', 'D'], ['MG', 'D'], ['Renault', 'D'], ['Seat', 'D'], ['Skoda', 'D'],
            ['Feat', 'D'], ['Subaru', 'D'], ['Opel', 'D'], ['Mini Cooper', 'D'], ['Ssang Yong', 'D'],
            ['TATA', 'D'], ['Volvo', 'D'], ['Cadillac', 'D'], ['Havel', 'D'], ['Lincoln', 'D'],
            ['Land Rover', 'D'], ['Peugeot', 'D'], ['Geely', 'D'], ['Havel & all other Chinese cars', 'D'],
            
            // Segment E
            ['Bentley', 'E'], ['Rolls-Royce', 'E'], ['Ferrari', 'E'], ['Bugatti', 'E'],
            ['Alfa Romeo', 'E'], ['Aston Martin', 'E'], ['Lamborghini', 'E'], ['Maserati', 'E'],
            ['Maybach', 'E'],
            
            // Segment F
            ['Motorbike', 'F'],
            
            // Segment G
            ['Lucid', 'G'],
        ];

        foreach ($brands as $brand) {
            [$name, $segment] = $brand;
            $carSegment = CarSegment::where('segment', $segment)->first();
            
            if ($carSegment) {
                Brand::create([
                    'name' => $name,
                    'slug' => strtolower(str_replace(' ', '-', $name)),
                    'car_segment_id' => $carSegment->id
                ]);
            }
        }
    
    }
}
