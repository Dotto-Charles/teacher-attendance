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
                'latitude' => null,
                'longitude' => null,
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
            [
                'name' => 'Pongai Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 17, // ✅ MONDO
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