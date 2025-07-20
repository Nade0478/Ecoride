<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Ajout d'un alias pour le modèle Covoiturage
use App\Models\Covoiturage as CovoiturageModel;

class Covoiturage extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de CovoiturageModel pour éviter la confusion
        $covoiturages = CovoiturageModel::with(['conducteur', 'passagers', 'trajet'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($covoiturages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'conducteur_id' => 'required|exists:utilisateurs,id',
            'trajet_id' => 'required|exists:trajets,id',
            'date_depart' => 'required|date',
            'heure_depart' => 'required|date_format:H:i',
            'places_disponibles' => 'required|integer|min:1',
        ]);

        // 🟨 Création via alias CovoiturageModel
        $covoiturage = CovoiturageModel::create($validatedData);

        return response()->json($covoiturage, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CovoiturageModel $covoiturage)
    {
        return response()->json($covoiturage->load(['conducteur', 'passagers', 'trajet']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CovoiturageModel $covoiturage)
    {
        $validatedData = $request->validate([
            'conducteur_id' => 'sometimes|exists:utilisateurs,id',
            'trajet_id' => 'sometimes|exists:trajets,id',
            'date_depart' => 'sometimes|date',
            'heure_depart' => 'sometimes|date_format:H:i',
            'places_disponibles' => 'sometimes|integer|min:1',
        ]);

        $covoiturage->update($validatedData);

        return response()->json($covoiturage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CovoiturageModel $covoiturage)
    {
        $covoiturage->delete();

        return response()->json(['message' => 'Covoiturage deleted successfully'], 204);
    }
}
