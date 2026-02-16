<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarModel;
use Carbon\Carbon;

class CarModelSeeder extends Seeder
{
    public function run()
    {
        // حذف جميع البيانات القديمة من الجدول
          CarModel::query()->delete();
        
        // أو يمكن استخدام:
        // CarModel::query()->delete();
        
        $currentYear = Carbon::now()->year;
        
        // إضافة السنوات من 2010 إلى السنة الحالية فقط
        for ($year = 2010; $year <= $currentYear; $year++) {
            CarModel::create([
                'model_year' => $year,
                'created_at' => now(),
                 'updated_at' => now()
            ]);
        }
        
        $this->command->info('تم حذف البيانات القديمة وإضافة سنوات الموديلات من 2000 إلى ' . $currentYear . ' بنجاح!');
    }
}