<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrailtySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('frailties')->insert([
            ['name' => 'Unintentional Weight Loss', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Exhaustion', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Weakness (Grip Strength)', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Slow Walking Speed', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Low Physical Activity', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
