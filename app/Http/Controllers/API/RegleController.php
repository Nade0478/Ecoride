<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Regle;
use Illuminate\Http\Request;

class RegleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regles = Regle::orderBy('created_at', 'desc')->get();
        return response()->json($regles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'actif' => 'required|boolean',
            'montant_credit' => 'required|numeric|min:0',
            'type_action' => 'required|string|max:255',
        ]);

        $regle = Regle::create($validatedData);

        return response()->json($regle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Regle $regle)
    {
        return response()->json($regle->load('mouvements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Regle $regle)
    {
        $validatedData = $request->validate([
            'actif' => 'sometimes|required|boolean',
            'montant_credit' => 'sometimes|required|numeric|min:0',
            'type_action' => 'sometimes|required|string|max:255',
        ]);

        $regle->update($validatedData);

        return response()->json($regle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regle $regle)
    {
        $regle->delete();

        return response()->json(['message' => 'Règle supprimée avec succès']);
    }

    /**
     * Get active rules only
     */
    public function active()
    {
        $regles = Regle::active()->get();
        return response()->json($regles);
    }

    /**
     * Get rules by type
     */
    public function byType($type)
    {
        $regles = Regle::byType($type)->get();
        return response()->json($regles);
    }
}