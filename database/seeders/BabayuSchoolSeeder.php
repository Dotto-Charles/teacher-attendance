<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BabayuSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Babayu Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Babayu Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chase Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
               [
                'name' => 'Masimba Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chinyika Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];


        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 1, // BABAYU
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