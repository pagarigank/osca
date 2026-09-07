<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PensionSourceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('pension_sources')->insert([
            ['name' => 'GSIS', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'SSS', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'PCSO', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Private Pension', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'AFP-RSBS', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'None', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
