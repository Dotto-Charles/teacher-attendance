<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChembaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Chemba Boys Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chemba Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kambi Nyasa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Chambalo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'St Joseph Pre & Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];


        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 3, // CHEMBA
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