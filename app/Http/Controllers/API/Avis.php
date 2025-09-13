<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Ajout d'un alias pour le modèle Avis
use App\Models\Avis as AvisModel;

class Avis extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de l'alias AvisModel au lieu de Avis
        $avis = AvisModel::with(['covoiturage', 'utilisateur'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($avis);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'covoiturage_id' => 'required|exists:covoiturages,id',
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:255',
            'statut' => 'nullable|string|max:50',
        ]);

        // 🟨 Utilisation de l'alias AvisModel au lieu de \App\Models\Avis
        $avis = new AvisModel();
        $avis->covoiturage_id = $request->covoiturage_id;
        $avis->utilisateur_id = $request->utilisateur_id;
        $avis->note = $request->note;
        $avis->commentaire = $request->commentaire;
        $avis->statut = $request->statut;
        $avis->save();
        return response()->json($avis, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AvisModel $avis)
    {
        return response()->json($avis->load(['covoiturage', 'utilisateur']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AvisModel $avis)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:255',
            'statut' => 'nullable|string|max:50',
        ]);
        $avis->note = $request->note;
        $avis->commentaire = $request->commentaire;
        $avis->statut = $request->statut;
        $avis->save();
        return response()->json($avis);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AvisModel $avis)
    {
        $avis->delete();
        return response()->json(null, 204);
    }
}
