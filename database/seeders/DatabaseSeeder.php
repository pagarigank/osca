<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // System & Auth
        $this->call([
            AccessLevelSeeder::class,
            OfficeSeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
        ]);

        // Library / Master Data
        $this->call([
            GenderSeeder::class,
            CivilStatusSeeder::class,
            NationalitySeeder::class,
            BarangaySeeder::class,
            LivingConditionSeeder::class,
            PlaceOfLivingSeeder::class,
            PayrollProviderSeeder::class,
            FundingSeeder::class,
            LocationSeeder::class,
            PensionSourceSeeder::class,
            OtherIncomeSeeder::class,
            RegularIncomeSeeder::class,
            DiagnosisSeeder::class,
            FrailtySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);

        // DSWD / Assessment
        $this->call([
            FactorConsiderationSeeder::class,
            ConsiderationScoringDetailSeeder::class,
            QuadParameterSeeder::class,
        ]);

        // Sample Transactions
        $this->call([
            SeniorCitizenSeeder::class,
            SeniorCitizenFundingSeeder::class,
            SeniorCitizenLivingChildrenSeeder::class,
        ]);
    }
}
