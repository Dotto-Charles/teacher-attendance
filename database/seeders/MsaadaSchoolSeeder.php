<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MsaadaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => "Songambele Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => "Msaada Primary School",
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Changamka Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Machiga Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Msaada Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
           
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 20, // ✅ MSAADA
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