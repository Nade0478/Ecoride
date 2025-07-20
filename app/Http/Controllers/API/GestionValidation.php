<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Alias ajouté pour le modèle GestionValidation
use App\Models\GestionValidation as GestionValidationModel;

class GestionValidation extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de l'alias GestionValidationModel
        $gestionValidations = GestionValidationModel::with(['covoiturage', 'utilisateur'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($gestionValidations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'covoiturage_id' => 'required|exists:covoiturages,id',
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'status' => 'required|string|max:255',
        ]);

        // 🟨 Instanciation via l'alias GestionValidationModel
        $gestionValidation = new GestionValidationModel();
        $gestionValidation->covoiturage_id = $request->covoiturage_id;
        $gestionValidation->utilisateur_id = $request->utilisateur_id;
        $gestionValidation->status = $request->status;
        $gestionValidation->save();

        return response()->json($gestionValidation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GestionValidationModel $gestionValidation)
    {
        return response()->json($gestionValidation->load(['covoiturage', 'utilisateur']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GestionValidationModel $gestionValidation)
    {
        $request->validate([
            'status' => 'sometimes|required|string|max:255',
        ]);

        if ($request->has('status')) {
            $gestionValidation->status = $request->status;
        }

        $gestionValidation->save();

        return response()->json($gestionValidation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GestionValidationModel $gestionValidation)
    {
        $gestionValidation->delete();
        return response()->json(null, 204);
    }
}
