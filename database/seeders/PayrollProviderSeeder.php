<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollProviderSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('payroll_providers')->insert([
            ['name' => 'GCash', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'PayMaya', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Land Bank', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'DBP', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cash', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Check', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
