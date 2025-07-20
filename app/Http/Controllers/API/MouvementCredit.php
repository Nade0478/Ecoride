<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Alias ajouté pour le modèle MouvementCredit
use App\Models\MouvementCredit as MouvementCreditModel;

class MouvementCredit extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // (Tu peux compléter ici selon tes besoins)
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mouvementCredit = new MouvementCreditModel(); // 🟨 Utilisation de l'alias
        $mouvementCredit->utilisateur_id = $request->utilisateur_id;
        $mouvementCredit->montant = $request->montant;
        $mouvementCredit->type = $request->type; // 'credit' or 'debit'
        $mouvementCredit->description = $request->description;
        $mouvementCredit->save();

        return response()->json($mouvementCredit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MouvementCreditModel $mouvementCredit) // 🟨 Alias dans la signature
    {
        return response()->json($mouvementCredit->load('utilisateur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MouvementCreditModel $mouvementCredit) // 🟨 Alias dans la signature
    {
        $mouvementCredit->montant = $request->montant ?? $mouvementCredit->montant;
        $mouvementCredit->type = $request->type ?? $mouvementCredit->type;
        $mouvementCredit->description = $request->description ?? $mouvementCredit->description;
        $mouvementCredit->save();

        return response()->json($mouvementCredit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MouvementCreditModel $mouvementCredit) // 🟨 Alias dans la signature
    {
        $mouvementCredit->delete();

        return response()->json(null, 204);
    }
}
