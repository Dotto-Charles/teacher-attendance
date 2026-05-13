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
        BabayuSchoolSeeder::class,
        ChandamaSchoolSeeder::class,
        ChembaSchoolSeeder::class,
        ChurukuSchoolSeeder::class,
        DalaiSchoolSeeder::class,
        FarkwaSchoolSeeder::class,
        GoimaSchoolSeeder::class,
        GwandiSchoolSeeder::class,
        JangaloSchoolSeeder::class,
        KidokaSchoolSeeder::class,
        KimahaSchoolSeeder::class,
        KinyamsindoSchoolSeeder::class,
        KwamtoroSchoolSeeder::class,
        LaltaSchoolSeeder::class,
        MakorongoSchoolSeeder::class,
        MondoSchoolSeeder::class,
        MpendoSchoolSeeder::class,
        MrijoSchoolSeeder::class,
        MsaadaSchoolSeeder::class,
        OvadaSchoolSeeder::class,
        ParangaSchoolSeeder::class,
        SanzawaSchoolSeeder::class,
        SongoloSchoolSeeder::class,
        SoyaSchoolSeeder::class,
        TumbakoseSchoolSeeder::class,
        UserSeeder::class, 

    ]);
}


}
