<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MrijoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Mrijo Chini Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Olboloti Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kalolen Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Isusumya Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Nkulari Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Magasa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => "Mrijo Chini 'B' Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mrijo Juu  Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Mrijo Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 18, // ✅ MRIJO
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