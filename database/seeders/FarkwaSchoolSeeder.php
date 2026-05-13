<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FarkwaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Farkwa Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Farkwa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Bugenika Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mombose Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Gonga Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Bubutole Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Donsee Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 6, // ✅ FARKWA
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