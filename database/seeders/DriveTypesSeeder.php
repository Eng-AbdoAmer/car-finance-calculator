<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DriveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $driveTypes = [
            ['name' => 'دفع أمامي', 'code' => 'FWD','created_at' => now(), 'updated_at' => now()],
            ['name' => 'دفع خلفي', 'code' => 'RWD','created_at' => now(), 'updated_at' => now()],
            ['name' => 'دفع رباعي دائم', 'code' => '4WD','created_at' => now(), 'updated_at' => now()],
            ['name' => 'دفع رباعي أوتوماتيك', 'code' => 'AWD','created_at' => now(), 'updated_at' => now()],
            ['name' => 'دفع رباعي تفاضلي', 'code' => '4x4','created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('drive_types')->insert($driveTypes);
    }
}
