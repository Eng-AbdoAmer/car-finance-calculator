<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CarCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           // 1. تعطيل فحص المفاتيح الخارجية مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 2. حذف جميع البيانات القديمة
        DB::table('car_categories')->delete();
        
        // 3. إعادة ضبط Auto Increment
        DB::statement('ALTER TABLE car_categories AUTO_INCREMENT = 1;');
        
        // 4. إعادة تفعيل فحص المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
          $categories = [
            ['name' => 'سيدان','created_at' => now(), 'updated_at' => now()],
            ['name' => 'دفع رباعي','created_at' => now(), 'updated_at' => now()],
            ['name' => 'كروس أوفر','created_at' => now(), 'updated_at' => now()],
            ['name' => 'هايبرد','created_at' => now(), 'updated_at' => now()],
            ['name' => 'رياضية','created_at' => now(), 'updated_at' => now()],
            ['name' => 'فان','created_at' => now(), 'updated_at' => now()],
            ['name' => 'بيك أب','created_at' => now(), 'updated_at' => now()],
            ['name' => 'كوبيه','created_at' => now(), 'updated_at' => now()],
            ['name' => 'مكشوفة','created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('car_categories')->insert($categories);
    }
}
