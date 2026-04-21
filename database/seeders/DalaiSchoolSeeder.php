<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Ward;

class DalaiSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $ward = Ward::where('name', 'Dalai')->first();

        if (!$ward) {
            return;
        }

        $schools = [
            [
                'name' => 'Dalai Secondary School',
                'latitude' => null,
                'longitude' => null,
            ],
            [
                'name' => 'Dalai Primary School',
                'latitude' => -5.01179,
                'longitude' => 35.96834,
            ],
            [
                'name' => 'Tandala Primary School',
                'latitude' => -4.95498,
                'longitude' => 36.01589,
            ],
            [
                'name' => 'Tandala B Primary School',
                'latitude' => -4.96674,
                'longitude' => 36.02192,
            ],
        ];

        foreach ($schools as $school) {
            School::firstOrCreate([
                'name' => $school['name'],
                'ward_id' => $ward->id,
            ], [
                'latitude' => $school['latitude'],
                'longitude' => $school['longitude'],
            ]);
        }
    }
}