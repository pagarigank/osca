<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactorConsiderationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('factor_considerations')->insert([
            ['name' => 'Age', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Health Status', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Economic Status', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Living Condition', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Social Support', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Frailty Indicators', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
