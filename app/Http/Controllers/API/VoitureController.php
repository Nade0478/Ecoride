<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Alias ajouté pour le modèle Voiture
use App\Models\Voiture as VoitureModel;

class Voiture extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de VoitureModel
        $voitures = VoitureModel::with(['marque', 'carModel'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($voitures);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'marque_id' => 'required|exists:marques,id',
            'car_model_id' => 'required|exists:car_models,id',
            'year' => 'required|integer|min:1886|max:' . date('Y'),
            'color' => 'required|string|max:50',
            'license_plate' => 'required|string|max:20|unique:voitures,license_plate',
        ]);

        // 🟨 Création via VoitureModel
        $voiture = VoitureModel::create($validatedData);

        return response()->json($voiture, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(VoitureModel $voiture) // 🟨 Alias dans la signature
    {
        return response()->json($voiture->load(['marque', 'carModel']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VoitureModel $voiture) // 🟨 Alias dans la signature
    {
        $validatedData = $request->validate([
            'marque_id' => 'sometimes|exists:marques,id',
            'car_model_id' => 'sometimes|exists:car_models,id',
            'year' => 'sometimes|integer|min:1886|max:' . date('Y'),
            'color' => 'sometimes|string|max:50',
            'license_plate' => 'sometimes|string|max:20|unique:voitures,license_plate,' . $voiture->id,
        ]);

        // 🟨 Mise à jour des champs
        $voiture->update($validatedData);

        return response()->json($voiture);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VoitureModel $voiture) // 🟨 Alias dans la signature
    {
        $voiture->delete();

        return response()->json(['message' => 'Voiture deleted successfully'], 200);
    }
}
