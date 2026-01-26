<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarSegment;
use App\Models\AgeBracket;
use App\Models\InsuranceRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsuranceRateSeeder extends Seeder
{
    public function run(): void
    {
        // تعطيل فحص المفاتيح الأجنبية مؤقتًا
        Schema::disableForeignKeyConstraints();
        
        // حذف البيانات القديمة
        InsuranceRate::truncate();
        
        // تمكين فحص المفاتيح الأجنبية
        Schema::enableForeignKeyConstraints();
        
        // بيانات الذكور
        $maleRates = [
            // 18-24
            ['18 to 24', 'A', 8.30], ['18 to 24', 'B', 7.20], ['18 to 24', 'C', 6.50],
            ['18 to 24', 'D', 8.00], ['18 to 24', 'E', 5.00], ['18 to 24', 'F', 6.00],
            ['18 to 24', 'G', 6.50],
            
            // 25-30
            ['25 to 30', 'A', 5.80], ['25 to 30', 'B', 5.50], ['25 to 30', 'C', 4.90],
            ['25 to 30', 'D', 6.60], ['25 to 30', 'E', 2.60], ['25 to 30', 'F', 5.00],
            ['25 to 30', 'G', 4.80],
            
            // 31-35
            ['31 to 35', 'A', 3.60], ['31 to 35', 'B', 3.50], ['31 to 35', 'C', 3.80],
            ['31 to 35', 'D', 4.50], ['31 to 35', 'E', 3.10], ['31 to 35', 'F', 3.10],
            ['31 to 35', 'G', 3.00],
            
            // 36-40
            ['36 to 40', 'A', 2.80], ['36 to 40', 'B', 2.80], ['36 to 40', 'C', 3.30],
            ['36 to 40', 'D', 3.80], ['36 to 40', 'E', 3.40], ['36 to 40', 'F', 2.90],
            ['36 to 40', 'G', 2.30],
            
            // 41-50
            ['41 to 50', 'A', 2.60], ['41 to 50', 'B', 2.60], ['41 to 50', 'C', 2.70],
            ['41 to 50', 'D', 3.60], ['41 to 50', 'E', 2.50], ['41 to 50', 'F', 3.00],
            ['41 to 50', 'G', 2.40],
            
            // 51-60
            ['51 to 60', 'A', 2.80], ['51 to 60', 'B', 2.80], ['51 to 60', 'C', 3.10],
            ['51 to 60', 'D', 3.60], ['51 to 60', 'E', 2.20], ['51 to 60', 'F', 3.00],
            ['51 to 60', 'G', 2.40],
            
            // 61 & above
            ['61 & above', 'A', 3.10], ['61 & above', 'B', 2.80], ['61 & above', 'C', 2.90],
            ['61 & above', 'D', 3.70], ['61 & above', 'E', 3.00], ['61 & above', 'F', 3.50],
            ['61 & above', 'G', 3.50],
        ];

        // بيانات الإناث
        $femaleRates = [
            // 18-24
            ['18 to 24', 'A', 6.30], ['18 to 24', 'B', 6.70], ['18 to 24', 'C', 8.40],
            ['18 to 24', 'D', 7.00], ['18 to 24', 'E', 5.50], ['18 to 24', 'F', 6.50],
            ['18 to 24', 'G', 7.00],
            
            // 25-30
            ['25 to 30', 'A', 5.60], ['25 to 30', 'B', 6.20], ['25 to 30', 'C', 5.40],
            ['25 to 30', 'D', 5.80], ['25 to 30', 'E', 6.60], ['25 to 30', 'F', 5.50],
            ['25 to 30', 'G', 5.10],
            
            // 31-35
            ['31 to 35', 'A', 4.00], ['31 to 35', 'B', 4.20], ['31 to 35', 'C', 4.80],
            ['31 to 35', 'D', 5.90], ['31 to 35', 'E', 5.00], ['31 to 35', 'F', 3.80],
            ['31 to 35', 'G', 3.20],
            
            // 36-40
            ['36 to 40', 'A', 3.50], ['36 to 40', 'B', 3.30], ['36 to 40', 'C', 3.90],
            ['36 to 40', 'D', 5.20], ['36 to 40', 'E', 3.50], ['36 to 40', 'F', 3.10],
            ['36 to 40', 'G', 3.00],
            
            // 41-50
            ['41 to 50', 'A', 3.60], ['41 to 50', 'B', 3.60], ['41 to 50', 'C', 4.10],
            ['41 to 50', 'D', 4.80], ['41 to 50', 'E', 4.80], ['41 to 50', 'F', 3.00],
            ['41 to 50', 'G', 2.70],
            
            // 51-60
            ['51 to 60', 'A', 4.00], ['51 to 60', 'B', 4.50], ['51 to 60', 'C', 4.50],
            ['51 to 60', 'D', 5.40], ['51 to 60', 'E', 2.50], ['51 to 60', 'F', 3.00],
            ['51 to 60', 'G', 2.60],
            
            // 61 & above
            ['61 & above', 'A', 4.80], ['61 & above', 'B', 4.70], ['61 & above', 'C', 4.40],
            ['61 & above', 'D', 5.60], ['61 & above', 'E', 6.00], ['61 & above', 'F', 3.50],
            ['61 & above', 'G', 3.50],
        ];

        $this->command->info('Seeding insurance rates...');
        
        // إضافة بيانات الذكور
        $maleCount = 0;
        foreach ($maleRates as $rate) {
            [$age, $segment, $rateValue] = $rate;
            if ($this->createRate('male', $age, $segment, $rateValue)) {
                $maleCount++;
            }
        }

        // إضافة بيانات الإناث
        $femaleCount = 0;
        foreach ($femaleRates as $rate) {
            [$age, $segment, $rateValue] = $rate;
            if ($this->createRate('female', $age, $segment, $rateValue)) {
                $femaleCount++;
            }
        }
        
        $this->command->info("Insurance rates seeded successfully!");
        $this->command->info("Total male rates: $maleCount");
        $this->command->info("Total female rates: $femaleCount");
        $this->command->info("Total rates: " . ($maleCount + $femaleCount));
    }

    private function createRate($gender, $ageName, $segmentName, $rateValue): bool
    {
        $ageBracket = AgeBracket::where('name', $ageName)->first();
        $carSegment = CarSegment::where('segment', $segmentName)->first();

        if (!$ageBracket) {
            $this->command->error("Age bracket not found: $ageName");
            return false;
        }
        
        if (!$carSegment) {
            $this->command->error("Car segment not found: $segmentName");
            return false;
        }

        try {
            InsuranceRate::create([
                'gender' => $gender,
                'age_bracket_id' => $ageBracket->id,
                'car_segment_id' => $carSegment->id,
                'rate' => $rateValue
            ]);
            
            $this->command->info("✓ Created: $gender | $ageName | $segmentName | $rateValue%");
            return true;
            
        } catch (\Exception $e) {
            $this->command->error("Failed to create rate: " . $e->getMessage());
            return false;
        }
    }
}