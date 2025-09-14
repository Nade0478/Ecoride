<?php

namespace Database\Seeders;

use Database\Seeders\AvisSeeder;
use Database\Seeders\CarModelSeeder;
use Database\Seeders\ConfigurationSeeder;
use Database\Seeders\CovoiturageSeeder;
use Database\Seeders\MarqueSeeder;
use Database\Seeders\MouvementSeeder;
use Database\Seeders\ParticipationSeeder;
use Database\Seeders\RegleSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UtilisateurSeeder;
use Database\Seeders\VoitureSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordre important pour respecter les contraintes de clés étrangères
        $this->call([
            // 1. Tables de base (sans dépendances)
            RoleSeeder::class,

            // 2. Utilisateurs (dépend des rôles)
            UtilisateurSeeder::class,

            // 3. Règles (indépendant)
            RegleSeeder::class,

            // 4. Autres tables qui dépendent des utilisateurs
            ConfigurationSeeder::class,
            MarqueSeeder::class,
            CarModelSeeder::class,
            VoitureSeeder::class,
            CovoiturageSeeder::class,

            // 5. Tables de liaison (dépendent des tables précédentes)
            ParticipationSeeder::class,
            MouvementSeeder::class,
            AvisSeeder::class,

            // 6. Notifications (en dernier car peut dépendre de tout)
            // NotificationSeeder::class,
        ]);

        // Alternative avec Factory pour les tests
        // $this->seedWithFactories();
    }

    /**
     * Alternative seeding using factories for testing
     */
    private function seedWithFactories(): void
    {
        // Créer des données de test avec les factories
        \App\Models\Utilisateur::factory(20)->create();
        \App\Models\Regle::factory(10)->create();
        \App\Models\Covoiturage::factory(15)->create();
        \App\Models\Participation::factory(50)->create();
        \App\Models\Mouvement::factory(100)->create();
    }
}