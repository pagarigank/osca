<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CivilStatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('civil_statuses')->insert([
            ['name' => 'Single', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Married', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Widowed', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Separated', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Divorced', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
