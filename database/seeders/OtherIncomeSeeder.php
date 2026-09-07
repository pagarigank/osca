<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtherIncomeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('other_incomes')->insert([
            ['name' => 'Rent Income', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Business Income', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Agricultural Income', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pension (Private)', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Remittances', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Stock Dividends', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
