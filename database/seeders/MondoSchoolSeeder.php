<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MondoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Mondo Secondary School',
                'latitude' => -4.9500000,
                'longitude' => 35.9800000,
            ],
            [
                'name' => 'Mondo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Araa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Waida Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 15, // ✅ MONDO
                    'code' => null,
                    'latitude' => $school['latitude'],
                    'longitude' => $school['longitude'],
                    'radius' => 500,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}