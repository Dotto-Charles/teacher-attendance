<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JangaloSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Jangalo Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Jangalo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Itolwa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chemka Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mlongia  Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 9, // ✅ JANGALO
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