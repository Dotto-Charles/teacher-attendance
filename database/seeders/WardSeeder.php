<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wards = [
            'Chandama',
            'Chemba',
            'Churuku',
            'Dalai',
            'Farkwa',
            'Goima',
            'Gwandi',
            'Jangalo',
            'Kidoka',
            'Kimaha',
            'Kinyamsindo',
            'Kwamtoro',
            'Lalta',
            'Makorongo',
            'Mondo',
            'Mpendo',
            'Mrijo',
            'Msaada',
            'Ovada',
            'Paranga',
            'Sanzawa',
            'Songoro',
            'Soya',
            'Tumbakose'
        ];

        foreach ($wards as $ward) {
            DB::table('wards')->insert([
                'name' => $ward,
                'council_id' => 1, // Chemba DC
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}