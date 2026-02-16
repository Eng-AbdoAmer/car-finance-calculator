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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Bank::query()->delete();
        DB::statement('ALTER TABLE banks AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $banks = [
            ['name' => 'بنك الأهلي','created_at' => now(), 'updated_at' => now()],
            ['name' => 'بنك الرياض','created_at' => now(), 'updated_at' => now()],
            ['name' => 'بنك البلاد','created_at' => now(), 'updated_at' => now()],
            ['name' => 'بنك الجزيرة','created_at' => now(), 'updated_at' => now()],
            ['name' => 'بنك الإنماء','created_at' => now(), 'updated_at' => now()],
            ['name' => 'البنك السعودي الفرنسي','created_at' => now(), 'updated_at' => now()],
            ['name' => 'شركة الراية للتمويل','created_at' => now(), 'updated_at' => now()],
            ['name' => 'شركة إمكان للتمويل','created_at' => now(), 'updated_at' => now()],
            ['name' => 'شركة عبد اللطيف جميل','created_at' => now(), 'updated_at' => now()],
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