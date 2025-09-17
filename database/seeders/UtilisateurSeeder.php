<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Utilisateur;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération dynamique des rôles
        $adminRole = Role::where('nom_role', 'admin')->first();
        $userRole = Role::where('nom_role', 'utilisateur')->first();
        $moderateurRole = Role::where('nom_role', 'moderateur')->first();
        $premiumRole = Role::where('nom_role', 'conducteur_premium')->first();

        // Vérification de l'existence des rôles
        if (!$adminRole || !$userRole || !$moderateurRole || !$premiumRole) {
            $this->command->error('Un ou plusieurs rôles sont manquants. Vérifiez le RoleSeeder.');
            return;
        }

        // Création des utilisateurs avec les rôles dynamiques
        Utilisateur::factory(10)->create([
            'id_role' => $userRole->id_role,
        ]);

        Utilisateur::factory(2)->create([
            'id_role' => $adminRole->id_role,
        ]);

        Utilisateur::factory(1)->create([
            'id_role' => $moderateurRole->id_role,
        ]);

        Utilisateur::factory(1)->create([
            'id_role' => $premiumRole->id_role,
        ]);
    }
}
