<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConsiderationScoringDetailSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Fetch factor IDs by name
        $factors = DB::table('factor_considerations')->pluck('id', 'name');

        $details = [
            // Age (Factor 1)
            ['factor' => 'Age', 'particular' => '60-64 years old', 'guide_points' => 10],
            ['factor' => 'Age', 'particular' => '65-69 years old', 'guide_points' => 15],
            ['factor' => 'Age', 'particular' => '70-74 years old', 'guide_points' => 20],
            ['factor' => 'Age', 'particular' => '75-79 years old', 'guide_points' => 25],
            ['factor' => 'Age', 'particular' => '80 years old and above', 'guide_points' => 30],

            // Health Status (Factor 2)
            ['factor' => 'Health Status', 'particular' => 'No known illness', 'guide_points' => 5],
            ['factor' => 'Health Status', 'particular' => 'With manageable illness (1-2 conditions)', 'guide_points' => 15],
            ['factor' => 'Health Status', 'particular' => 'With multiple chronic conditions (3+)', 'guide_points' => 25],
            ['factor' => 'Health Status', 'particular' => 'Bedridden or hospitalized', 'guide_points' => 30],

            // Economic Status (Factor 3)
            ['factor' => 'Economic Status', 'particular' => 'With stable income/pension', 'guide_points' => 5],
            ['factor' => 'Economic Status', 'particular' => 'Irregular income', 'guide_points' => 15],
            ['factor' => 'Economic Status', 'particular' => 'No source of income', 'guide_points' => 25],
            ['factor' => 'Economic Status', 'particular' => 'Indigent/4Ps beneficiary', 'guide_points' => 30],

            // Living Condition (Factor 4)
            ['factor' => 'Living Condition', 'particular' => 'Own house, decent living', 'guide_points' => 5],
            ['factor' => 'Living Condition', 'particular' => 'Rented/shared housing', 'guide_points' => 10],
            ['factor' => 'Living Condition', 'particular' => 'Living in informal settler area', 'guide_points' => 20],
            ['factor' => 'Living Condition', 'particular' => 'Homeless/street dweller', 'guide_points' => 30],

            // Social Support (Factor 5)
            ['factor' => 'Social Support', 'particular' => 'Has family support', 'guide_points' => 5],
            ['factor' => 'Social Support', 'particular' => 'Minimal family contact', 'guide_points' => 15],
            ['factor' => 'Social Support', 'particular' => 'Living alone, no support', 'guide_points' => 25],
            ['factor' => 'Social Support', 'particular' => 'Abandoned/Neglected', 'guide_points' => 30],

            // Frailty Indicators (Factor 6)
            ['factor' => 'Frailty Indicators', 'particular' => 'No signs of frailty', 'guide_points' => 5],
            ['factor' => 'Frailty Indicators', 'particular' => 'Pre-frail (1-2 indicators)', 'guide_points' => 15],
            ['factor' => 'Frailty Indicators', 'particular' => 'Frail (3+ indicators)', 'guide_points' => 25],
            ['factor' => 'Frailty Indicators', 'particular' => 'Severely frail/bedridden', 'guide_points' => 30],
        ];

        foreach ($details as $d) {
            DB::table('consideration_scoring_details')->insert([
                'factor_consideration_id' => $factors[$d['factor']],
                'particular' => $d['particular'],
                'guide_points' => $d['guide_points'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
