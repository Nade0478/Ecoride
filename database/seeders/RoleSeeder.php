<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider la table avant d'insérer de nouveaux rôles
        DB::table('roles')->truncate();

        // Définition des rôles avec leurs permissions
        $roles = [
            ['id_role' => 1, 'nom_role' => 'visiteur', 'permissions' => json_encode(['consulter_covoiturages', 'rechercher_trajets'])],
            ['id_role' => 2, 'nom_role' => 'passager', 'permissions' => json_encode(['consulter_covoiturages', 'participer_covoiturage'])],
            ['id_role' => 3, 'nom_role' => 'chauffeur', 'permissions' => json_encode(['creer_covoiturage', 'gerer_vehicules'])],
            ['id_role' => 4, 'nom_role' => 'employe', 'permissions' => json_encode(['valider_avis', 'gerer_incidents'])],
            ['id_role' => 5, 'nom_role' => 'administrateur', 'permissions' => json_encode(['creer_employes', 'suspendre_comptes'])],
        ];

        // Insertion directe avec DB pour éviter les problèmes de cast
        DB::table('roles')->insert($roles);

        $this->command->info('Rôles EcoRide créés avec succès !');
    }
}