<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $visiteurRole = Role::where('nom_role', 'visiteur')->first();
        $passagerRole = Role::where('nom_role', 'passager')->first();
        $chauffeurRole = Role::where('nom_role', 'chauffeur')->first();
        $employeRole = Role::where('nom_role', 'employe')->first();
        $adminRole = Role::where('nom_role', 'administrateur')->first();

        if (!$visiteurRole || !$passagerRole || !$chauffeurRole || !$employeRole || !$adminRole) {
            $this->command->error('Un ou plusieurs rôles EcoRide sont manquants.');
            return;
        }

        User::factory(15)->create(['id_role' => $passagerRole->id_role]);
        User::factory(8)->create(['id_role' => $chauffeurRole->id_role]);
        User::factory(2)->create(['id_role' => $employeRole->id_role]);
        User::factory(1)->create(['id_role' => $adminRole->id_role]);
        User::factory(3)->create(['id_role' => $visiteurRole->id_role]);

        $this->command->info('Utilisateurs EcoRide créés avec succès !');
    }
}