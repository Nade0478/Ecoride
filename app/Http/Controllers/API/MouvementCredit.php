<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MouvementCredit;
use App\Models\Utilisateur;

class MouvementCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = MouvementCredit::with(['utilisateur:id_utilisateur,nom,prenom', 'covoiturage', 'regleCredit']);

            // Filtrer par utilisateur si spécifié
            if ($request->has('utilisateur_id')) {
                $query->where('id_utilisateur', $request->utilisateur_id);
            }

            // Filtrer par type de mouvement
            if ($request->has('type_mouvement')) {
                $query->where('type_mouvement', $request->type_mouvement);
            }

            // Filtrer par période
            if ($request->has('date_debut')) {
                $query->whereDate('date_operation', '>=', $request->date_debut);
            }
            if ($request->has('date_fin')) {
                $query->whereDate('date_operation', '<=', $request->date_fin);
            }

            $mouvements = $query->orderBy('date_operation', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $mouvements
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des mouvements',
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
            // Validation selon votre MCD
            $validatedData = $request->validate([
                'id_utilisateur' => 'required|exists:utilisateur,id_utilisateur',
                'type_mouvement' => 'required|in:credit,debit',
                'montant' => 'required|integer', // Peut être négatif pour les débits
                'description' => 'required|string|max:255',
                'id_regle_credit' => 'nullable|exists:regle_credit,id_regle',
                'id_covoiturage' => 'nullable|exists:covoiturage,id_covoiturage',
            ]);

            // Valeurs automatiques
            $validatedData['date_operation'] = now();

            // Logique métier : les débits doivent être négatifs
            if ($validatedData['type_mouvement'] === 'debit' && $validatedData['montant'] > 0) {
                $validatedData['montant'] = -$validatedData['montant'];
            }

            // Vérification du solde pour les débits
            if ($validatedData['type_mouvement'] === 'debit') {
                $soldeActuel = $this->calculerSolde($validatedData['id_utilisateur']);
                if ($soldeActuel + $validatedData['montant'] < 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Solde insuffisant',
                        'solde_actuel' => $soldeActuel,
                        'montant_demande' => abs($validatedData['montant'])
                    ], 422);
                }
            }

            $mouvementCredit = MouvementCredit::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Mouvement créé avec succès',
                'data' => $mouvementCredit->load(['utilisateur', 'covoiturage', 'regleCredit']),
                'nouveau_solde' => $this->calculerSolde($validatedData['id_utilisateur'])
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
            $mouvementCredit = MouvementCredit::with([
                'utilisateur:id_utilisateur,nom,prenom,email',
                'covoiturage:id_covoiturage,lieu_depart,lieu_arrivee,date_depart',
                'regleCredit:id_regle,nom_regle,type_action'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $mouvementCredit
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mouvement non trouvé'
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
     * Note: En général, on ne modifie pas les mouvements de crédit pour l'audit
     */
    public function update(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Modification non autorisée - Les mouvements de crédit sont immuables pour des raisons d\'audit'
        ], 403);
    }

    /**
     * Remove the specified resource from storage.
     * Note: En général, on ne supprime pas les mouvements de crédit
     */
    public function destroy($id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Suppression non autorisée - Les mouvements de crédit sont conservés pour l\'historique'
        ], 403);
    }

    /**
     * Calculer le solde d'un utilisateur
     */
    private function calculerSolde($utilisateurId)
    {
        return MouvementCredit::where('id_utilisateur', $utilisateurId)->sum('montant');
    }

    /**
     * Obtenir le solde d'un utilisateur
     */
    public function solde(Request $request, $utilisateurId)
    {
        try {
            // Vérifier que l'utilisateur existe
            $utilisateur = Utilisateur::findOrFail($utilisateurId);

            $solde = $this->calculerSolde($utilisateurId);

            // Détail des mouvements récents
            $derniersMovements = MouvementCredit::where('id_utilisateur', $utilisateurId)
                ->with(['covoiturage:id_covoiturage,lieu_depart,lieu_arrivee', 'regleCredit:id_regle,nom_regle'])
                ->orderBy('date_operation', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'utilisateur' => $utilisateur->only(['id_utilisateur', 'nom', 'prenom']),
                    'solde_total' => $solde,
                    'derniers_mouvements' => $derniersMovements
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul du solde',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Attribuer des crédits selon une règle
     */
    public function attribuerCredits(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_utilisateur' => 'required|exists:utilisateur,id_utilisateur',
                'id_regle_credit' => 'required|exists:regle_credit,id_regle',
                'id_covoiturage' => 'nullable|exists:covoiturage,id_covoiturage',
                'description_supplementaire' => 'nullable|string|max:255'
            ]);

            // Récupérer la règle de crédit
            $regle = \App\Models\MouvementCredit::findOrFail($validatedData['id_regle_credit']);

            if (!$regle->actif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette règle de crédit n\'est plus active'
                ], 422);
            }

            // Créer le mouvement de crédit
            $mouvement = MouvementCredit::create([
                'id_utilisateur' => $validatedData['id_utilisateur'],
                'type_mouvement' => 'credit',
                'montant' => $regle->montant_credit,
                'description' => $validatedData['description_supplementaire'] ?? $regle->nom_regle,
                'id_regle_credit' => $regle->id_regle,
                'id_covoiturage' => $validatedData['id_covoiturage'] ?? null,
                'date_operation' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Crédits attribués avec succès selon la règle '{$regle->nom_regle}'",
                'data' => $mouvement->load(['utilisateur', 'regleCredit']),
                'nouveau_solde' => $this->calculerSolde($validatedData['id_utilisateur'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'attribution des crédits',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}