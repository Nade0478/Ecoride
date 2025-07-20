<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Alias ajouté pour le modèle Marque
use App\Models\Marque as MarqueModel;

class Marque extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de l'alias MarqueModel
        $marques = MarqueModel::with(['carModel'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($marques);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Add other fields as necessary
        ]);

        // 🟨 Création via l'alias MarqueModel
        $marque = MarqueModel::create($validatedData);

        return response()->json($marque, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MarqueModel $marque)
    {
        return response()->json($marque->load(['carModel']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarqueModel $marque)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            // Add other fields as necessary
        ]);

        $marque->update($validatedData);

        return response()->json($marque);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarqueModel $marque)
    {
        $marque->delete();

        return response()->json(['message' => 'Marque deleted successfully'], 204);
    }
}
