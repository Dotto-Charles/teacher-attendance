<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KwamtoroSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => "Kwamtoro  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => "Ndoroboni Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Banguma Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Msera Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kurio Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mialo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Tamka Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kwamtoro Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 13, // ✅ KWAMTORO
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