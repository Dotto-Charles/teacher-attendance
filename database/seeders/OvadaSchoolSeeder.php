<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OvadaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => "Kilimba Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => "Jogolo Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Baaba Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Dinae Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Ovada  Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
           
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 21, // ✅ OVADA
                    'code' => null,
                    'latitude' => $school['latitude'],
                    'longitude' => $school['longitude'],
                    'radius' => 50,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}