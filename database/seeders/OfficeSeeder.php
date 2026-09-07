<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('offices')->insert([
            ['name' => 'OSCA - Office of Senior Citizens Affairs', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'HR - Human Resources', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'IT - Information Technology', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Finance', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'General Services', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'City Mayor Office', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'DSWD - City Social Welfare', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
