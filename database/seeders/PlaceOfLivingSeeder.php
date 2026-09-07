<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaceOfLivingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('place_of_livings')->insert([
            ['name' => 'Own House', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rented', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Living with Relatives', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Government Housing', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Informal Settler', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
