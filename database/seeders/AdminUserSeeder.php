<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'full_name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('AdminPassword123!'),
                'phone_number' => '0000000000',
                'role' => 'admin',
                'date_of_birth' => '2000-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
