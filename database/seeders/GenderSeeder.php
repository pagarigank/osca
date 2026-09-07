<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('genders')->insert([
            ['name' => 'Male', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Female', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
