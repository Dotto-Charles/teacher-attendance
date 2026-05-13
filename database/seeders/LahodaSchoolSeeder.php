<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LahodaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Lahoda Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Lahoda Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kisande Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Handa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Handa B Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 14, // ✅ LAHODA
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