<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeniorCitizenLivingChildrenSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->where('email', 'admin@osca.com')->value('id');
        $scIds = DB::table('senior_citizens')->pluck('id', 'id_number');

        $children = [
            // M-0001 Rosario Cruz
            ['senior_citizen_id' => $scIds['M-0001'], 'last_name' => 'Cruz', 'first_name' => 'Juan Jr.', 'mobile_no' => '09171111111'],
            ['senior_citizen_id' => $scIds['M-0001'], 'last_name' => 'Cruz', 'first_name' => 'Ana', 'mobile_no' => '09172222222'],

            // M-0002 Pedro Reyes
            ['senior_citizen_id' => $scIds['M-0002'], 'last_name' => 'Reyes', 'first_name' => 'Pedro Jr.', 'mobile_no' => '09181111111'],
            ['senior_citizen_id' => $scIds['M-0002'], 'last_name' => 'Reyes', 'first_name' => 'Jose', 'mobile_no' => '09182222222'],
            ['senior_citizen_id' => $scIds['M-0002'], 'last_name' => 'Reyes', 'first_name' => 'Patricia', 'mobile_no' => '09183333333'],

            // M-0004 Miguel Santos
            ['senior_citizen_id' => $scIds['M-0004'], 'last_name' => 'Santos', 'first_name' => 'Miguel Jr.', 'mobile_no' => '09241111111'],

            // M-0006 Roberto Aquino
            ['senior_citizen_id' => $scIds['M-0006'], 'last_name' => 'Aquino', 'first_name' => 'Carmen', 'mobile_no' => '09281111111'],
            ['senior_citizen_id' => $scIds['M-0006'], 'last_name' => 'Aquino', 'first_name' => 'Roberto Jr.', 'mobile_no' => '09282222222'],

            // M-0008 Fernando Mendoza
            ['senior_citizen_id' => $scIds['M-0008'], 'last_name' => 'Mendoza', 'first_name' => 'Rosa', 'mobile_no' => '09321111111'],

            // M-0009 Guadalupe Flores
            ['senior_citizen_id' => $scIds['M-0009'], 'last_name' => 'Flores', 'first_name' => 'Ernesto', 'mobile_no' => '09341111111'],
            ['senior_citizen_id' => $scIds['M-0009'], 'last_name' => 'Flores', 'first_name' => 'Gloria', 'mobile_no' => '09342222222'],
        ];

        foreach ($children as $c) {
            DB::table('senior_citizen_living_children')->insert(array_merge($c, [
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
