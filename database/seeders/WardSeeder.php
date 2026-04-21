<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;
use App\Models\Council;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        // Hakikisha council ya Chemba ipo
        $council = Council::firstOrCreate([
            'name' => 'Chemba District Council'
        ]);

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
            Ward::firstOrCreate([
                'name' => $ward,
                'council_id' => $council->id
            ]);
        }
    }
}