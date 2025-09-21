<?php

namespace Database\Seeders;

use App\Models\Mouvement;
use App\Models\User;
use App\Models\Regle;
use App\Models\Covoiturage;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MouvementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::limit(10)->get();
        $regles = Regle::where('actif', true)->get();
        $covoiturages = Covoiturage::limit(5)->get();

        if ($users->isEmpty()) {
            $this->command->warn('Assurez-vous d\'avoir des users avant de lancer ce seeder');
            return;
        }

        $typesMouvements = ['credit', 'debit'];
        $descriptions = [
            'credit' => [
                'Bonus d\'inscription',
                'Crédit covoiturage conducteur',
                'Crédit covoiturage passager',
                'Bonus parrainage',
                'Crédit avis positif',
                'Bonus mensuel'
            ],
            'debit' => [
                'Achat produit éco',
                'Participation événement',
                'Réservation véhicule',
                'Service premium',
                'Frais annulation'
            ]
        ];

        foreach ($users as $user) {
            // Créer 3-8 mouvements par user
            $nombreMouvements = rand(3, 8);

            for ($i = 0; $i < $nombreMouvements; $i++) {
                $typeMouvement = $typesMouvements[array_rand($typesMouvements)];
                $dateOperation = Carbon::now()->subDays(rand(1, 60));

                // Définir le montant selon le type
                if ($typeMouvement === 'credit') {
                    $montant = rand(100, 2000) / 100;
                    $regle = $regles->isNotEmpty() ? $regles->random() : null;
                    $description = $descriptions['credit'][array_rand($descriptions['credit'])];
                } else {
                    $montant = rand(50, 1500) / 100;
                    $regle = null;
                    $description = $descriptions['debit'][array_rand($descriptions['debit'])];
                }

                $mouvement = [
                    'type_mouvement' => $typeMouvement,
                    'montant' => $montant,
                    'date_operation' => $dateOperation,
                    'description' => $description,
                    'id_regle_credit' => $regle?->id_regle,
                    'id_covoiturage' => ($typeMouvement === 'credit' && $covoiturages->isNotEmpty())
                        ? $covoiturages->random()->id_covoiturage
                        : null,
                    'id_user' => $user->id,
                    'created_at' => $dateOperation,
                    'updated_at' => now(),
                ];

                Mouvement::create($mouvement);
            }
        }

        // Créer quelques mouvements spéciaux pour demo
        $this->createSpecialMovements($users->first(), $regles);
    }

    /**
     * Créer des mouvements spéciaux pour la démonstration
     */
    private function createSpecialMovements($user, $regles)
    {
        if (!$user || $regles->isEmpty()) return;

        $mouvementsSpeciaux = [
            [
                'type_mouvement' => 'credit',
                'montant' => 10.00,
                'description' => 'Bonus d\'inscription - Bienvenue !',
                'id_regle_credit' => $regles->where('type_action', 'inscription')->first()?->id_regle,
            ],
            [
                'type_mouvement' => 'credit',
                'montant' => 5.00,
                'description' => 'Crédit covoiturage en tant que conducteur',
                'id_regle_credit' => $regles->where('type_action', 'covoiturage_conducteur')->first()?->id_regle,
            ],
            [
                'type_mouvement' => 'debit',
                'montant' => 3.50,
                'description' => 'Achat produit écologique - Gourde réutilisable',
            ],
        ];

        foreach ($mouvementsSpeciaux as $mouvement) {
            Mouvement::create(array_merge($mouvement, [
                'date_operation' => now()->subDays(rand(1, 7)),
                'id_user' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}