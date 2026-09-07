<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $offices = DB::table('offices')->pluck('id', 'name');

        $positions = [
            ['office' => 'OSCA - Office of Senior Citizens Affairs', 'name' => 'OSCA Head'],
            ['office' => 'OSCA - Office of Senior Citizens Affairs', 'name' => 'Administrative Aide'],
            ['office' => 'OSCA - Office of Senior Citizens Affairs', 'name' => 'Records Officer'],
            ['office' => 'OSCA - Office of Senior Citizens Affairs', 'name' => 'Encoder'],
            ['office' => 'HR - Human Resources', 'name' => 'HR Director'],
            ['office' => 'HR - Human Resources', 'name' => 'HR Specialist'],
            ['office' => 'HR - Human Resources', 'name' => 'HR Staff'],
            ['office' => 'IT - Information Technology', 'name' => 'IT Manager'],
            ['office' => 'IT - Information Technology', 'name' => 'Web Developer'],
            ['office' => 'Finance', 'name' => 'City Accountant'],
            ['office' => 'Finance', 'name' => 'Budget Officer'],
            ['office' => 'City Mayor Office', 'name' => 'City Mayor'],
            ['office' => 'City Mayor Office', 'name' => 'City Administrator'],
            ['office' => 'DSWD - City Social Welfare', 'name' => 'DSWD Head'],
            ['office' => 'DSWD - City Social Welfare', 'name' => 'Social Worker'],
        ];

        foreach ($positions as $p) {
            DB::table('positions')->insert([
                'office_id' => $offices[$p['office']],
                'name' => $p['name'],
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
