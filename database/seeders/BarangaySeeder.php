<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $barangays = [
            'Atlu-Bola', 'Batis', 'Cambangatugal', 'Caoayan', 'Dolores',
            'Duquit', 'Lakandula', 'Mabiga', 'Mabuco', 'Matumtum',
            'Palinan', 'Poblacion', 'San Francisco', 'San Joaquin', 'San Jose',
            'San Juan', 'San Nicolas', 'San Pedro', 'Sapang Biabas', 'Sapalib',
            'Sapang Uwak', 'Sapayas', 'Santo Niño', 'Sapang Maragul', 'Tabun',
        ];

        $records = [];
        foreach ($barangays as $i => $name) {
            $records[] = [
                'name' => $name,
                'prefix' => $name === 'Poblacion' ? 'Pob.' : null,
                'id_sequence' => $i + 1,
                'pay_type' => in_array($name, ['Poblacion', 'San Joaquin', 'San Juan', 'San Nicolas', 'San Pedro']) ? 'CASHA' : 'BANKA',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('barangays')->insert($records);
    }
}
