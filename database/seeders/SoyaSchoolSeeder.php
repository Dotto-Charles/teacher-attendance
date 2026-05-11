<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoyaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => "Chang'ombe  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => "Soya  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Magandi Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mbarada Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mkadinde  Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Soya Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 24, // ✅ SOYA
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