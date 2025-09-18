<?php

namespace Database\Seeders;

use App\Models\Participation;
use App\Models\User;
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
        // Récupérer quelques users et covoiturages pour créer des participations
        $users = User::limit(10)->get();
        $covoiturages = Covoiturage::limit(5)->get();

        if ($users->isEmpty() || $covoiturages->isEmpty()) {
            $this->command->warn('Assurez-vous d\'avoir des users et covoiturages avant de lancer ce seeder');
            return;
        }

        $statuts = ['en_attente', 'confirmee', 'annulee'];
        $participations = [];

        foreach ($covoiturages as $covoiturage) {
            // Créer 2-4 participations par covoiturage
            $nombreParticipants = rand(2, 4);
            $usersSelectionnes = $users->random($nombreParticipants);

            foreach ($usersSelectionnes as $user) {
                $dateInscription = Carbon::now()->subDays(rand(1, 30));
                $statut = $statuts[array_rand($statuts)];

                $participation = [
                    'id_covoiturage' => $covoiturage->id_covoiturage,
                    'id_user' => $user->id,
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
                'id_user' => $participation['id_user']
            ])->exists();

            if (!$exists) {
                Participation::create($participation);
            }
        }
    }
}