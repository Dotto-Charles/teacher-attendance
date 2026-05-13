<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaltaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Lalta Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Doyo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Wairo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Lalta Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Manantu Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 15, // ✅ LALTA
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