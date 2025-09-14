<?php

namespace Database\Seeders;

use App\Models\Participation;
use App\Models\Utilisateur;
use App\Models\Covoiturage;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ParticipationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques utilisateurs et covoiturages pour créer des participations
        $utilisateurs = Utilisateur::limit(10)->get();
        $covoiturages = Covoiturage::limit(5)->get();

        if ($utilisateurs->isEmpty() || $covoiturages->isEmpty()) {
            $this->command->warn('Assurez-vous d\'avoir des utilisateurs et covoiturages avant de lancer ce seeder');
            return;
        }

        $statuts = ['en_attente', 'confirmee', 'annulee'];
        $participations = [];

        foreach ($covoiturages as $covoiturage) {
            // Créer 2-4 participations par covoiturage
            $nombreParticipants = rand(2, 4);
            $utilisateursSelectionnes = $utilisateurs->random($nombreParticipants);

            foreach ($utilisateursSelectionnes as $utilisateur) {
                $dateInscription = Carbon::now()->subDays(rand(1, 30));
                $statut = $statuts[array_rand($statuts)];

                $participation = [
                    'id_covoiturage' => $covoiturage->id_covoiturage,
                    'id_utilisateur' => $utilisateur->id,
                    'date_inscription' => $dateInscription,
                    'date_validation' => $statut === 'confirmee' ? $dateInscription->addHours(rand(1, 24)) : null,
                    'statut' => $statut,
                    'presente' => $statut === 'confirmee' ? (bool)rand(0, 1) : false,
                    'created_at' => $dateInscription,
                    'updated_at' => now(),
                ];

                $participations[] = $participation;
            }
        }

        // Éviter les doublons avant insertion
        foreach ($participations as $participation) {
            $exists = Participation::where([
                'id_covoiturage' => $participation['id_covoiturage'],
                'id_utilisateur' => $participation['id_utilisateur']
            ])->exists();

            if (!$exists) {
                Participation::create($participation);
            }
        }
    }
}