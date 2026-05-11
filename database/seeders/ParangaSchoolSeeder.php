<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParangaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Paranga Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kuu Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kelema Balai Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Sori Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Paranga Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Isini Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Cheku Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 21, // ✅ PARANGA
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