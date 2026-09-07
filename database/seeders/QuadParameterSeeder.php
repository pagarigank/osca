<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuadParameterSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('quad_parameters')->insert([
            // QUAD1 - Age
            ['quad_type' => 'QUAD1', 'name' => '60-64 years', 'points' => 10.00, 'age_from' => 60, 'age_to' => 64, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD1', 'name' => '65-69 years', 'points' => 15.00, 'age_from' => 65, 'age_to' => 69, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD1', 'name' => '70-74 years', 'points' => 20.00, 'age_from' => 70, 'age_to' => 74, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD1', 'name' => '75-79 years', 'points' => 25.00, 'age_from' => 75, 'age_to' => 79, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD1', 'name' => '80+ years', 'points' => 30.00, 'age_from' => 80, 'age_to' => 120, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('quad_parameters')->insert([
            // QUAD2 - Health
            ['quad_type' => 'QUAD2', 'name' => 'No illness', 'points' => 5.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD2', 'name' => 'Mild illness', 'points' => 15.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD2', 'name' => 'Moderate illness', 'points' => 20.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD2', 'name' => 'Severe/bedridden', 'points' => 30.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('quad_parameters')->insert([
            // QUAD3 - Economic
            ['quad_type' => 'QUAD3', 'name' => 'With stable income', 'points' => 5.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD3', 'name' => 'Irregular income', 'points' => 15.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD3', 'name' => 'No income/Indigent', 'points' => 25.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('quad_parameters')->insert([
            // QUAD4 - Living Condition
            ['quad_type' => 'QUAD4', 'name' => 'Own house, decent', 'points' => 5.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD4', 'name' => 'Rented/shared', 'points' => 10.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD4', 'name' => 'Informal settler', 'points' => 20.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['quad_type' => 'QUAD4', 'name' => 'Homeless', 'points' => 30.00, 'age_from' => null, 'age_to' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
