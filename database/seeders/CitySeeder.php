<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $provinces = DB::table('provinces')->pluck('id', 'name');

        $cities = [
            ['province' => 'Pampanga', 'name' => 'Mabalacat City'],
            ['province' => 'Pampanga', 'name' => 'Angeles City'],
            ['province' => 'Pampanga', 'name' => 'San Fernando City'],
            ['province' => 'Pampanga', 'name' => 'City of San Simon'],
            ['province' => 'Bulacan', 'name' => 'San Jose del Monte City'],
            ['province' => 'Bulacan', 'name' => 'Meycauayan City'],
            ['province' => 'Tarlac', 'name' => 'Tarlac City'],
            ['province' => 'Tarlac', 'name' => 'Capas'],
            ['province' => 'Metro Manila', 'name' => 'Quezon City'],
            ['province' => 'Metro Manila', 'name' => 'Manila'],
        ];

        foreach ($cities as $c) {
            DB::table('cities')->insert([
                'province_id' => $provinces[$c['province']],
                'name' => $c['name'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
