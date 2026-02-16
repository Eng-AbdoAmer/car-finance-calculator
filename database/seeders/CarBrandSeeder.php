<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarBrand;
use Illuminate\Support\Str;

class CarBrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'تويوتا', 'لكزس', 'نيسان', 'هوندا', 'ميتسوبيشي',  
            'سوزوكي', 'دايهاتسو', 'إيسوزو',
            'هيونداي', 'كيا', 'دايو',
            'MG',
            'مرسيدس-بنز', 'بي إم دبليو', 'أودي', 'بورش', 'أوبل',
            'سكودا',
            'فيكتوري',
            'فورد', 'شيفروليه', 'جيب', 'جيم سي', 'همر',
            'تسلا',
        ];

        foreach ($brands as $brand) {
            CarBrand::updateOrCreate([
                'name' => $brand,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('✅ تم إضافة ' . count($brands) . ' علامة تجارية');
    }
}