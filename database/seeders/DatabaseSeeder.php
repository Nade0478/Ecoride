<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            RegleSeeder::class,
            ConfigurationSeeder::class,
            MarqueSeeder::class,
            CarModelSeeder::class,
            VoitureSeeder::class,
            CovoiturageSeeder::class,
            ParticipationSeeder::class,
            MouvementSeeder::class,
            AvisSeeder::class,
        ]);
    }
}