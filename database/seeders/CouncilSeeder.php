<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouncilSeeder extends Seeder
{
    public function run(): void
    {
        $councils = [
            [
                'id' => 1,
                'name' => 'Chemba District Council',
            ],
        ];

        foreach ($councils as $council) {
            DB::table('councils')->updateOrInsert(
                ['id' => $council['id']],
                [
                    'name' => $council['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}