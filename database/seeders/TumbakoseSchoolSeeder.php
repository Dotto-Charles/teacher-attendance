<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TumbakoseSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => "Tumbakose  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => "Humekwa  Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Hawelo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 26, // ✅ TUMBAKOSE
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