<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegularIncomeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('regular_incomes')->insert([
            ['name' => 'Salary/Wages', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Self-Employed', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Freelance/Contract', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pension (Government)', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sari-Sari Store', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'None', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
