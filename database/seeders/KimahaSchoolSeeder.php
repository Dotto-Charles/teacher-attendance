<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KimahaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Kimaha Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Lugoba Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mwailanje Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Wisuzaje Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chukuruma Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Mwaikisabe Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 11, // ✅ KIMAHA
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