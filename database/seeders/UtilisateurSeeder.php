<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Utilisateur::factory(10)->create([
            'role' => 'user',
        ]);

        \App\Models\Utilisateur::factory(2)->create([
            'role' => 'admin',
        ]);

        \App\Models\Utilisateur::factory(1)->create([
            'role' => 'super_admin',
        ]);

        \App\Models\Utilisateur::factory(1)->create([
            'role' => 'guest',
        ]);
    }
}
