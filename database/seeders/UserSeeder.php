<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'abdo@gmail.com'], 
            [
                'name' => 'Abdo Amer',
                'phone' => '0563793577',
                'type' => 'admin',
                'password' => Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user created successfully');
    }
}
