<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer les configurations existantes
        Configuration::query()->delete();

        $this->seedGeneralConfigurations();
        $this->seedCovoiturageConfigurations();
        $this->seedPaiementConfigurations();
        $this->seedNotificationConfigurations();
        $this->seedModerationConfigurations();
        $this->seedDashboardConfigurations();
        $this->seedSecuriteConfigurations();

        $this->command->info('Configurations EcoRide créées avec succès!');
    }

    private function seedGeneralConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'nom_plateforme',
                'categorie' => 'general',
                'valeur' => 'EcoRide',
                'type_donnee' => 'string',
                'description' => 'Nom de la plateforme de covoiturage',
                'modifiable_interface' => true,
                'cache_requis' => true
            ],
            [
                'cle' => 'version_application',
                'categorie' => 'general',
                'valeur' => '1.0.0',
                'type_donnee' => 'string',
                'description' => 'Version actuelle de l\'application',
                'modifiable_interface' => false,
                'cache_requis' => false
            ],
            [
                'cle' => 'maintenance_mode',
                'categorie' => 'general',
                'valeur' => 'false',
                'type_donnee' => 'boolean',
                'description' => 'Mode maintenance de l\'application',
                'modifiable_interface' => true,
                'cache_requis' => true
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }

    private function seedCovoiturageConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'prix_par_km',
                'categorie' => 'covoiturage',
                'valeur' => '0.65',
                'type_donnee' => 'float',
                'description' => 'Prix de base par kilomètre (€)',
                'modifiable_interface' => true,
                'cache_requis' => true
            ],
            [
                'cle' => 'max_passagers',
                'categorie' => 'covoiturage',
                'valeur' => '3',
                'type_donnee' => 'integer',
                'description' => 'Nombre maximum de passagers par trajet',
                'modifiable_interface' => true,
                'cache_requis' => true
            ],
            [
                'cle' => 'commission_plateforme',
                'categorie' => 'covoiturage',
                'valeur' => '0.15',
                'type_donnee' => 'float',
                'description' => 'Commission de la plateforme (15%)',
                'modifiable_interface' => true,
                'cache_requis' => true
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }

    private function seedModerationConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'moderation_avis_automatique',
                'categorie' => 'moderation',
                'valeur' => 'false',
                'type_donnee' => 'boolean',
                'description' => 'Activation de la modération automatique des avis',
                'modifiable_interface' => true,
                'cache_requis' => true
            ],
            [
                'cle' => 'delai_validation_avis',
                'categorie' => 'moderation',
                'valeur' => '48',
                'type_donnee' => 'integer',
                'description' => 'Délai maximum pour valider un avis (heures)',
                'modifiable_interface' => true,
                'cache_requis' => false
            ],
            [
                'cle' => 'signalement_seuil_suspension',
                'categorie' => 'moderation',
                'valeur' => '3',
                'type_donnee' => 'integer',
                'description' => 'Nombre de signalements avant suspension automatique',
                'modifiable_interface' => true,
                'cache_requis' => true
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }

    private function seedDashboardConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'dashboard_periode_defaut',
                'categorie' => 'dashboard',
                'valeur' => '30',
                'type_donnee' => 'integer',
                'description' => 'Période par défaut des graphiques (jours)',
                'modifiable_interface' => true,
                'cache_requis' => false
            ],
            [
                'cle' => 'affichage_total_credits_plateforme',
                'categorie' => 'dashboard',
                'valeur' => 'true',
                'type_donnee' => 'boolean',
                'description' => 'Afficher le total des crédits gagnés par la plateforme',
                'modifiable_interface' => true,
                'cache_requis' => false
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }

    private function seedSecuriteConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'duree_suspension_defaut',
                'categorie' => 'securite',
                'valeur' => '7',
                'type_donnee' => 'integer',
                'description' => 'Durée par défaut d\'une suspension (jours)',
                'modifiable_interface' => true,
                'cache_requis' => false
            ],
            [
                'cle' => 'notification_admin_suspension',
                'categorie' => 'securite',
                'valeur' => 'true',
                'type_donnee' => 'boolean',
                'description' => 'Notifier les admins lors d\'une suspension',
                'modifiable_interface' => true,
                'cache_requis' => false
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }

    private function seedNotificationConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'notification_nouveau_avis',
                'categorie' => 'notifications',
                'valeur' => 'true',
                'type_donnee' => 'boolean',
                'description' => 'Notifier les employés des nouveaux avis à modérer',
                'modifiable_interface' => true,
                'cache_requis' => true
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }

    private function seedPaiementConfigurations(): void
    {
        $configurations = [
            [
                'cle' => 'credit_initial_inscription',
                'categorie' => 'paiement',
                'valeur' => '10.00',
                'type_donnee' => 'float',
                'description' => 'Crédits offerts à l\'inscription (€)',
                'modifiable_interface' => true,
                'cache_requis' => true
            ]
        ];

        foreach ($configurations as $configuration) {
            Configuration::create($configuration);
        }
    }
}