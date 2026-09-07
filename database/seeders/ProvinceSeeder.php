<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('provinces')->insert([
            ['name' => 'Pampanga', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bulacan', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tarlac', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Zambales', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Metro Manila', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
