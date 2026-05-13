<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MakorongoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Makorongo Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Maziwa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Makorongo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Khubunko Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 16, // ✅ MAKORONGO
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