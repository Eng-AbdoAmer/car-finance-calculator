<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تعطيل فحص المفاتيح الأجنبية لحذف البيانات
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Bank::query()->delete();
        DB::statement('ALTER TABLE banks AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $banks = [
            ['name' => 'بنك الأهلي'],
            ['name' => 'بنك الرياض'],
            ['name' => 'بنك البلاد'],
            ['name' => 'بنك الجزيرة'],
            ['name' => 'بنك الإنماء'],
            ['name' => 'البنك السعودي الفرنسي'],
            ['name' => 'شركة الراية للتمويل'],
            ['name' => 'شركة إمكان للتمويل'],
            ['name' => 'شركة عبد اللطيف جميل'],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['name' => $bank['name']],
                $bank
            );
        }
        
        $this->command->info('تم إضافة ' . count($banks) . ' بنك بنجاح!');
    }
}