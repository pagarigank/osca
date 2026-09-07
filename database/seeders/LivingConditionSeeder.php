<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LivingConditionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('living_conditions')->insert([
            ['name' => 'Living Alone', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Living with Spouse', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Living with Children', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Living with Relatives', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Living with Friends', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'In a Care Facility', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
