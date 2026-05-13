<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChandamaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Chandama Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chandama Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mapango Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];


        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 2, // CHANDAMA
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