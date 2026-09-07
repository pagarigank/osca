<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $locations = [
            ['province' => 'Pampanga', 'city_municipality' => 'Mabalacat City'],
            ['province' => 'Pampanga', 'city_municipality' => 'Angeles City'],
            ['province' => 'Pampanga', 'city_municipality' => 'San Fernando City'],
            ['province' => 'Pampanga', 'city_municipality' => 'Capas, Tarlac'],
            ['province' => 'Pampanga', 'city_municipality' => 'Clark Freeport Zone'],
            ['province' => 'Bulacan', 'city_municipality' => 'San Jose del Monte City'],
            ['province' => 'Bulacan', 'city_municipality' => 'Meycauayan City'],
            ['province' => 'Metro Manila', 'city_municipality' => 'Quezon City'],
            ['province' => 'Metro Manila', 'city_municipality' => 'Manila'],
            ['province' => 'Metro Manila', 'city_municipality' => 'Caloocan City'],
        ];

        foreach ($locations as $loc) {
            DB::table('locations')->insert([
                'province' => $loc['province'],
                'city_municipality' => $loc['city_municipality'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
