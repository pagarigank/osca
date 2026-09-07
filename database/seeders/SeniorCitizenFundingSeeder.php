<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeniorCitizenFundingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->where('email', 'admin@osca.com')->value('id');
        $scIds = DB::table('senior_citizens')->pluck('id', 'id_number');
        $pensionSources = DB::table('pension_sources')->pluck('id', 'name');
        $fundings = DB::table('fundings')->pluck('id', 'fund_source');
        $payrollProviders = DB::table('payroll_providers')->pluck('id', 'name');
        $otherIncomes = DB::table('other_incomes')->pluck('id', 'name');

        $fundings_data = [
            [
                'senior_citizen_id' => $scIds['M-0001'],
                'pension_source_id' => $pensionSources['GSIS'],
                'monthly_pension' => 3500.00,
                'funding_id' => $fundings['DBM - Social Pension'],
                'payroll_provider_id' => $payrollProviders['GCash'],
                'regular_income_type' => 'Pension (Government)',
                'regular_income_amount' => 3500.00,
                'other_income_id' => null,
                'other_income_amount' => null,
            ],
            [
                'senior_citizen_id' => $scIds['M-0002'],
                'pension_source_id' => $pensionSources['SSS'],
                'monthly_pension' => 2800.00,
                'funding_id' => $fundings['Mabalacat City - Senior Citizen Fund'],
                'payroll_provider_id' => $payrollProviders['Land Bank'],
                'regular_income_type' => 'Self-Employed',
                'regular_income_amount' => 5000.00,
                'other_income_id' => $otherIncomes['Rent Income'],
                'other_income_amount' => 2000.00,
            ],
            [
                'senior_citizen_id' => $scIds['M-0003'],
                'pension_source_id' => $pensionSources['None'],
                'monthly_pension' => null,
                'funding_id' => $fundings['DBM - Social Pension'],
                'payroll_provider_id' => $payrollProviders['Cash'],
                'regular_income_type' => 'None',
                'regular_income_amount' => null,
                'other_income_id' => null,
                'other_income_amount' => null,
            ],
            [
                'senior_citizen_id' => $scIds['M-0004'],
                'pension_source_id' => $pensionSources['GSIS'],
                'monthly_pension' => 5200.00,
                'funding_id' => $fundings['GAD - Gender and Development'],
                'payroll_provider_id' => $payrollProviders['DBP'],
                'regular_income_type' => 'Salary/Wages',
                'regular_income_amount' => 8000.00,
                'other_income_id' => $otherIncomes['Agricultural Income'],
                'other_income_amount' => 3000.00,
            ],
            [
                'senior_citizen_id' => $scIds['M-0005'],
                'pension_source_id' => $pensionSources['PCSO'],
                'monthly_pension' => 1500.00,
                'funding_id' => $fundings['DSWD - Assistance to Individuals in Crisis'],
                'payroll_provider_id' => $payrollProviders['Cash'],
                'regular_income_type' => 'Sari-Sari Store',
                'regular_income_amount' => 3000.00,
                'other_income_id' => null,
                'other_income_amount' => null,
            ],
            [
                'senior_citizen_id' => $scIds['M-0006'],
                'pension_source_id' => $pensionSources['GSIS'],
                'monthly_pension' => 4000.00,
                'funding_id' => $fundings['DBM - Social Pension'],
                'payroll_provider_id' => $payrollProviders['GCash'],
                'regular_income_type' => 'Salary/Wages',
                'regular_income_amount' => 10000.00,
                'other_income_id' => $otherIncomes['Business Income'],
                'other_income_amount' => 5000.00,
            ],
            [
                'senior_citizen_id' => $scIds['M-0007'],
                'pension_source_id' => $pensionSources['None'],
                'monthly_pension' => null,
                'funding_id' => $fundings['Mabalacat City - Senior Citizen Fund'],
                'payroll_provider_id' => $payrollProviders['Cash'],
                'regular_income_type' => 'None',
                'regular_income_amount' => null,
                'other_income_id' => $otherIncomes['Remittances'],
                'other_income_amount' => 5000.00,
            ],
            [
                'senior_citizen_id' => $scIds['M-0008'],
                'pension_source_id' => $pensionSources['Private Pension'],
                'monthly_pension' => 6000.00,
                'funding_id' => $fundings['PCSO - Medical Assistance'],
                'payroll_provider_id' => $payrollProviders['Land Bank'],
                'regular_income_type' => 'Pension (Government)',
                'regular_income_amount' => 6000.00,
                'other_income_id' => $otherIncomes['Stock Dividends'],
                'other_income_amount' => 1000.00,
            ],
            [
                'senior_citizen_id' => $scIds['M-0009'],
                'pension_source_id' => $pensionSources['SSS'],
                'monthly_pension' => 2500.00,
                'funding_id' => $fundings['DBM - Social Pension'],
                'payroll_provider_id' => $payrollProviders['PayMaya'],
                'regular_income_type' => 'Freelance/Contract',
                'regular_income_amount' => 4000.00,
                'other_income_id' => null,
                'other_income_amount' => null,
            ],
            [
                'senior_citizen_id' => $scIds['M-0010'],
                'pension_source_id' => $pensionSources['None'],
                'monthly_pension' => null,
                'funding_id' => $fundings['DSWD - Assistance to Individuals in Crisis'],
                'payroll_provider_id' => $payrollProviders['Cash'],
                'regular_income_type' => 'None',
                'regular_income_amount' => null,
                'other_income_id' => null,
                'other_income_amount' => null,
            ],
        ];

        foreach ($fundings_data as $f) {
            DB::table('senior_citizen_fundings')->insert(array_merge($f, [
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
