<?php

namespace Database\Seeders;

use App\Models\Covoiturage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CovoiturageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Covoiturage::factory(20)->create();
    }
}
