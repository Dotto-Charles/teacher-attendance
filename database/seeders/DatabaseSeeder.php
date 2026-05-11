<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        
        CouncilSeeder::class,
        WardSeeder::class,
        DalaiSchoolSeeder::class,
        MondoSchoolSeeder::class,
        SongoloSchoolSeeder::class,
        SoyaSchoolSeeder::class,
        TumbakoseSchoolSeeder::class,
        SanzawaSchoolSeeder::class,
        ParangaSchoolSeeder::class,
        OvadaSchoolSeeder::class,
        MsaadaSchoolSeeder::class,
        MrijoSchoolSeeder::class,
        MpendoSchoolSeeder::class,
        MakorongoSchoolSeeder::class,
        LaltaSchoolSeeder::class,
        KwamtoroSchoolSeeder::class,
        KinyamsindoSchoolSeeder::class,
        UserSeeder::class, 

    ]);
}


}
