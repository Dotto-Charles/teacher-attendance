<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KidokaSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Kidoka Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Kidoka Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Pangalua Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => ' Ombiri Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Muungano Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
             [
                'name' => 'Aldersgate Primary School',
                'latitude' => null,
                'longitude' => null,
            ],
        ];

        foreach ($schools as $school) {
            DB::table('schools')->updateOrInsert(
                ['name' => $school['name']],
                [
                    'ward_id' => 10, // ✅ KIDOKA
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