<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('nationalities')->insert([
            ['name' => 'Filipino', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'American', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Japanese', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chinese', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Korean', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Indian', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'British', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
