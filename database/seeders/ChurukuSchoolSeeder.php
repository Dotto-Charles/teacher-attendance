<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChurukuSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Churuku Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Churuku Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Jinjo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kinkima Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];


        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 4, // CHURUKU
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