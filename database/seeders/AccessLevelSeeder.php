<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessLevelSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('access_levels')->insert([
            ['name' => 'Administrator', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Supervisor', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Encoder', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Viewer', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
