<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FundingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('fundings')->insert([
            [
                'provider' => 'NATIONAL',
                'fund_source' => 'DBM - Social Pension',
                'amount' => 500.00,
                'effective_date' => '2026-01-01',
                'beneficiary' => 100,
                'total' => 50000.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'NATIONAL',
                'fund_source' => 'GAD - Gender and Development',
                'amount' => 750.00,
                'effective_date' => '2026-01-01',
                'beneficiary' => 50,
                'total' => 37500.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'LGU',
                'fund_source' => 'Mabalacat City - Senior Citizen Fund',
                'amount' => 1000.00,
                'effective_date' => '2026-01-01',
                'beneficiary' => 200,
                'total' => 200000.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'LGU',
                'fund_source' => 'DSWD - Assistance to Individuals in Crisis',
                'amount' => 2000.00,
                'effective_date' => '2026-06-01',
                'beneficiary' => 30,
                'total' => 60000.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'provider' => 'PRIVATE',
                'fund_source' => 'PCSO - Medical Assistance',
                'amount' => 1500.00,
                'effective_date' => '2026-03-01',
                'beneficiary' => 20,
                'total' => 30000.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
