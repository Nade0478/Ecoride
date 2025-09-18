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
        // Vider la table avant d'insérer
        DB::table('roles')->truncate();

        // Utiliser des insertions SQL brutes pour éviter les problèmes de cast JSON
        $roles = [
            [
                'id_role' => 1,
                'nom-role' => 'admin',
                'permissions' => json_encode(['all', 'create', 'read', 'update', 'delete', 'manage_users', 'manage_system']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 2,
                'nom-role' => 'user',
                'permissions' => json_encode(['read', 'create_covoiturage', 'participate']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 3,
                'nom-role' => 'moderateur',
                'permissions' => json_encode(['read', 'update', 'moderate_content', 'manage_covoiturages']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 4,
                'nom-role' => 'conducteur_premium',
                'permissions' => json_encode(['read', 'create_covoiturage', 'participate', 'premium_features']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insertion directe avec DB pour éviter les problèmes de cast
        DB::table('roles')->insert($roles);

        $this->command->info('Rôles créés avec succès !');
    }
}