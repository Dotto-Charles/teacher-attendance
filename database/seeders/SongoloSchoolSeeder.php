<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SongoloSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Chioli Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Madaha Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Songolo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Hamai  Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Songolo Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 23, // ✅ SONGOLO
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