<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GwandiSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Gwandi Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Gwandi Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Rofati Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Hanaa Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 8, // ✅ GWANDI
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