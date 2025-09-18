<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Covoiturage;
use App\Models\User;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participations = Participation::with(['user', 'covoiturage'])
            ->orderBy('date_inscription', 'desc')
            ->get();

        return response()->json($participations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_covoiturage' => 'required|exists:covoiturages,id_covoiturage',
            'id_user' => 'required|exists:users,id',
            'statut' => 'required|string|in:en_attente,confirmee,annulee',
            'presente' => 'sometimes|boolean',
        ]);

        // Vérifier si la participation n'existe pas déjà
        $existingParticipation = Participation::where([
            'id_covoiturage' => $validatedData['id_covoiturage'],
            'id_user' => $validatedData['id_user']
        ])->first();

        if ($existingParticipation) {
            return response()->json([
                'message' => 'Cette participation existe déjà'
            ], 409);
        }

        $participation = Participation::create($validatedData);

        return response()->json($participation->load(['user', 'covoiturage']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($idCovoiturage, $idUser)
    {
        $participation = Participation::where([
            'id_covoiturage' => $idCovoiturage,
            'id_user' => $idUser
        ])->with(['user', 'covoiturage'])->first();

        if (!$participation) {
            return response()->json(['message' => 'Participation non trouvée'], 404);
        }

        return response()->json($participation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idCovoiturage, $idUser)
    {
        $participation = Participation::where([
            'id_covoiturage' => $idCovoiturage,
            'id_user' => $idUser
        ])->first();

        if (!$participation) {
            return response()->json(['message' => 'Participation non trouvée'], 404);
        }

        $validatedData = $request->validate([
            'statut' => 'sometimes|required|string|in:en_attente,confirmee,annulee',
            'presente' => 'sometimes|boolean',
            'date_validation' => 'sometimes|nullable|date',
        ]);

        $participation->update($validatedData);

        return response()->json($participation->load(['user', 'covoiturage']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idCovoiturage, $idUser)
    {
        $participation = Participation::where([
            'id_covoiturage' => $idCovoiturage,
            'id_user' => $idUser
        ])->first();

        if (!$participation) {
            return response()->json(['message' => 'Participation non trouvée'], 404);
        }

        $participation->delete();

        return response()->json(['message' => 'Participation supprimée avec succès']);
    }

    /**
     * Get participations by user
     */
    public function getParticipationsByUser($Iduser)
    {
        $participations = Participation::where('id_user', $Iduser)
            ->with('covoiturage')
            ->get();

        return response()->json($participations);
    }

    /**
     * Get participations by covoiturage
     */
    public function getParticipationsByCovoiturage($Idcovoiturage)
    {
        $participations = Participation::where('id_covoiturage', $Idcovoiturage)
            ->with('user')
            ->get();

        return response()->json($participations);
    }

    /**
     * Confirm participation
     */
    public function confirmer($idCovoiturage, $idUser)
    {
        $participation = Participation::where([
            'id_covoiturage' => $idCovoiturage,
            'id_user' => $idUser
        ])->first();

        if (!$participation) {
            return response()->json(['message' => 'Participation non trouvée'], 404);
        }

        $participation->update([
            'statut' => 'confirmee',
            'date_validation' => now()
        ]);

        return response()->json($participation);
    }
}