<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiagnosisSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $diagnoses = [
            ['generic_name' => 'Essential Hypertension', 'icd10_code' => 'I10', 'icd10_short_name' => 'Essential (primary) hypertension', 'icd10_long_name' => 'Essential (primary) hypertension'],
            ['generic_name' => 'Type 2 Diabetes Mellitus', 'icd10_code' => 'E11', 'icd10_short_name' => 'Type 2 diabetes mellitus', 'icd10_long_name' => 'Type 2 diabetes mellitus without complications'],
            ['generic_name' => 'Osteoarthritis', 'icd10_code' => 'M15-M19', 'icd10_short_name' => 'Osteoarthritis', 'icd10_long_name' => 'Osteoarthritis'],
            ['generic_name' => 'Cataract', 'icd10_code' => 'H25', 'icd10_short_name' => 'Senile cataract', 'icd10_long_name' => 'Senile cataract'],
            ['generic_name' => 'Chronic Obstructive Pulmonary Disease', 'icd10_code' => 'J44', 'icd10_short_name' => 'COPD', 'icd10_long_name' => 'Other chronic obstructive pulmonary disease'],
            ['generic_name' => 'Heart Failure', 'icd10_code' => 'I50', 'icd10_short_name' => 'Heart failure', 'icd10_long_name' => 'Heart failure'],
            ['generic_name' => 'Cerebrovascular Disease', 'icd10_code' => 'I60-I69', 'icd10_short_name' => 'Cerebrovascular disease', 'icd10_long_name' => 'Diseases of cerebrovascular system'],
            ['generic_name' => 'Dyslipidemia', 'icd10_code' => 'E78', 'icd10_short_name' => 'Dyslipidemia', 'icd10_long_name' => 'Disorders of lipoprotein metabolism and other lipidemias'],
            ['generic_name' => 'Anemia', 'icd10_code' => 'D64', 'icd10_short_name' => 'Other anemias', 'icd10_long_name' => 'Other anemias'],
            ['generic_name' => 'Urinary Tract Infection', 'icd10_code' => 'N39.0', 'icd10_short_name' => 'UTI', 'icd10_long_name' => 'Urinary tract infection, site not specified'],
            ['generic_name' => 'Depression', 'icd10_code' => 'F32', 'icd10_short_name' => 'Depressive episode', 'icd10_long_name' => 'Depressive episode'],
            ['generic_name' => 'Dementia', 'icd10_code' => 'F00-F03', 'icd10_short_name' => 'Dementia', 'icd10_long_name' => 'Dementia in other diseases'],
        ];

        foreach ($diagnoses as $d) {
            DB::table('diagnoses')->insert(array_merge($d, [
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
