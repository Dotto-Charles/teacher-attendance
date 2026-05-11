<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DalaiSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Dalai Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Dalai Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Tandala Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Tandala B Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Taqwa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Piho Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kelema Maziwani Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kelema Maziwani Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Mtakuja Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];


        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 5, // Dalai Ward
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