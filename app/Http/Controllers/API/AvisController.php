<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Avis as AvisModel;

class Avis extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $avis = AvisModel::with(['covoiturage', 'user'])
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
            'id_covoiturage' => 'required|exists:covoiturages,id_covoiturage',
            'id_user' => 'required|exists:users,id_user',
            'valeur' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:255',
            'statut' => 'nullable|string|max:50',
        ]);

        $avis = new AvisModel();
        $avis->id_covoiturage = $request->id_covoiturage;
        $avis->id_user = $request->id_user;
        $avis->valeur = $request->valeur;
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
        return response()->json($avis->load(['covoiturage', 'user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AvisModel $avis)
    {
        $request->validate([
            'valeur' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:255',
            'statut' => 'nullable|string|max:50',
        ]);

        $avis->valeur = $request->valeur;
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
