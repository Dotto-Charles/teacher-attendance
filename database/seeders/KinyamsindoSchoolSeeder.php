<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KinyamsindoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Kinyamsindo Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Takwa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kinyamsindo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mengu Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 12, // ✅ KINYAMSINDO
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