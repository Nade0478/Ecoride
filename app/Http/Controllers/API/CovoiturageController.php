<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Covoiturage;
use App\Models\User;

class CovoiturageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $covoiturages = Covoiturage::with([
                'organisateur:id_user,nom,prenom,photo',
                'participations.user:id_user,nom,prenom,photo',
                'voiture:id_voiture,immatriculation,couleur,energie'
            ])
            ->where('statut', '!=', 'annule')
            ->orderBy('date_depart', 'asc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $covoiturages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des covoiturages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'organisateur_id' => 'required|exists:users,id_user',
                'date_depart' => 'required|date|after:now',
                'heure_depart' => 'required|date_format:H:i',
                'date_arrivee' => 'nullable|date|after_or_equal:date_depart',
                'heure_arrivee' => 'nullable|date_format:H:i',
                'lieu_depart' => 'required|string|max:255',
                'lieu_arrivee' => 'required|string|max:255',
                'nombre_places' => 'required|integer|min:1|max:8',
                'prix_credit' => 'required|integer|min:0',
                'ecologique' => 'boolean',
                'accepte_fumeur' => 'boolean',
                'accepte_animaux' => 'boolean',
            ]);

            // Valeurs par défaut
            $validatedData['statut'] = 'en_attente';
            $validatedData['ecologique'] = $validatedData['ecologique'] ?? false;
            $validatedData['accepte_fumeur'] = $validatedData['accepte_fumeur'] ?? false;
            $validatedData['accepte_animaux'] = $validatedData['accepte_animaux'] ?? false;

            $covoiturage = Covoiturage::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Covoiturage créé avec succès',
                'data' => $covoiturage->load('organisateur')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $covoiturage = Covoiturage::with([
                'organisateur:id_user,nom,prenom,photo,telephone',
                'participations' => function($query) {
                    $query->where('statut', '!=', 'refuse')
                          ->with('user:id_user,nom,prenom,photo');
                },
                'voiture.marque',
                'avis'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $covoiturage
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Covoiturage non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $covoiturage = Covoiturage::findOrFail($id);

            // Empêcher la modification si le covoiturage est terminé
            if (in_array($covoiturage->statut, ['termine', 'annule'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de modifier un covoiturage terminé ou annulé'
                ], 422);
            }

            $validatedData = $request->validate([
                'date_depart' => 'sometimes|date|after:now',
                'heure_depart' => 'sometimes|date_format:H:i',
                'date_arrivee' => 'sometimes|date|after_or_equal:date_depart',
                'heure_arrivee' => 'sometimes|date_format:H:i',
                'lieu_depart' => 'sometimes|string|max:255',
                'lieu_arrivee' => 'sometimes|string|max:255',
                'nombre_places' => 'sometimes|integer|min:1|max:8',
                'prix_credit' => 'sometimes|integer|min:0',
                'ecologique' => 'sometimes|boolean',
                'accepte_fumeur' => 'sometimes|boolean',
                'accepte_animaux' => 'sometimes|boolean',
                'statut' => 'sometimes|in:en_attente,confirme,en_cours,termine,annule',
            ]);

            $covoiturage->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Covoiturage mis à jour avec succès',
                'data' => $covoiturage->load('organisateur')
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Covoiturage non trouvé'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $covoiturage = Covoiturage::findOrFail($id);

            // Soft delete si il y a des participants
            if ($covoiturage->participations()->count() > 0) {
                $covoiturage->update(['statut' => 'annule']);
                $message = 'Covoiturage annulé (participants présents)';
            } else {
                $covoiturage->delete();
                $message = 'Covoiturage supprimé avec succès';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Covoiturage non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Participer à un covoiturage
     */
    public function participer(Request $request, $id)
    {
        try {
            $covoiturage = Covoiturage::findOrFail($id);

            $validatedData = $request->validate([
                'id_user' => 'required|exists:user,id_user'
            ]);

            $id_user = $validatedData['id_user'];

            // Vérifications métier
            if ($covoiturage->organisateur_id == $id_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas participer à votre propre covoiturage'
                ], 422);
            }

            if ($covoiturage->statut !== 'en_attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce covoiturage n\'accepte plus de participants'
                ], 422);
            }

            // Vérifier si l'user participe déjà
            $participationExistante = $covoiturage->participations()
                ->where('id_user', $id_user)
                ->first();

            if ($participationExistante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous participez déjà à ce covoiturage'
                ], 422);
            }

            // Vérifier le nombre de places disponibles
            $participationsActuelles = $covoiturage->participations()
                ->whereIn('statut', ['accepte', 'presente'])
                ->count();

            if ($participationsActuelles >= $covoiturage->nombre_places) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plus de places disponibles'
                ], 422);
            }

            // TODO: Vérifier les crédits de l'user
            // $user = user::find($id_user);
            // $solde = $user->calculerSoldeCredits();
            // if ($solde < $covoiturage->prix_credit) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Crédits insuffisants'
            //     ], 422);
            // }

            // Créer la participation
            $participation = $covoiturage->participations()->create([
                'id_user' => $id_user,
                'statut' => 'en_attente',
                'date_inscription' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Demande de participation envoyée',
                'data' => $participation->load('user')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la participation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accepter ou refuser un participant
     */
    public function gererParticipation(Request $request, $id, $Idparticipation)
    {
        try {
            $covoiturage = Covoiturage::findOrFail($id);

            $validatedData = $request->validate([
                'statut' => 'required|in:accepte,refuse',
                'organisateur_id' => 'required|exists:user,id_user'
            ]);

            // Vérifier que l'user est bien l'organisateur
            if ($covoiturage->organisateur_id != $validatedData['organisateur_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seul l\'organisateur peut gérer les participations'
                ], 403);
            }

            $participation = $covoiturage->participations()->findOrFail($Idparticipation);

            $participation->update([
                'statut' => $validatedData['statut'],
                'date_validation' => now()
            ]);

            $message = $validatedData['statut'] === 'accepte'
                ? 'Participation acceptée'
                : 'Participation refusée';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $participation->load('user')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la gestion de la participation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}