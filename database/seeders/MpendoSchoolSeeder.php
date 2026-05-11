<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MpendoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Mpendo Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Hamia Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Mpendo Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Magungu Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kubi Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 17, // ✅ MPENDO
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