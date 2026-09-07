<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $offices = DB::table('offices')->pluck('id', 'name');
        $accessLevels = DB::table('access_levels')->pluck('id', 'name');

        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@osca.com',
                'password' => Hash::make('password'),
                'office_id' => $offices['OSCA - Office of Senior Citizens Affairs'],
                'access_level_id' => $accessLevels['Administrator'],
                'is_active' => true,
                'approval_status' => 'APPROVED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Juan Dela Cruz',
                'username' => 'encoder1',
                'email' => 'encoder@osca.com',
                'password' => Hash::make('password'),
                'office_id' => $offices['OSCA - Office of Senior Citizens Affairs'],
                'access_level_id' => $accessLevels['Encoder'],
                'is_active' => true,
                'approval_status' => 'APPROVED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Maria Santos',
                'username' => 'supervisor1',
                'email' => 'supervisor@osca.com',
                'password' => Hash::make('password'),
                'office_id' => $offices['OSCA - Office of Senior Citizens Affairs'],
                'access_level_id' => $accessLevels['Supervisor'],
                'is_active' => true,
                'approval_status' => 'APPROVED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pedro Reyes',
                'username' => 'dswd1',
                'email' => 'dswd@osca.com',
                'password' => Hash::make('password'),
                'office_id' => $offices['DSWD - City Social Welfare'],
                'access_level_id' => $accessLevels['Encoder'],
                'is_active' => true,
                'approval_status' => 'APPROVED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
