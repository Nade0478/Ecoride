<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\GestionValidation;
use Illuminate\Database\Seeder;

class Gestion_ValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GestionValidation::factory(20)->create();
    }
}
