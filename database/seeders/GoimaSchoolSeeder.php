<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoimaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Goima Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Goima Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Makamaka Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mirambo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Jenjeluse Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Igunga Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 7, // ✅ Goima
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