<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanzawaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => "Dedu  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => "Birise  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Sanzawa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Motto Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mangasta Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Sanzawa Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 23, // ✅ SANZAWA
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