<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Liste tous les réglages
     */
    public function index()
    {
        return response()->json(Configuration::all());
    }

    /**
     * Affiche un réglage par son ID
     */
    public function show($id)
    {
        $configuration = Configuration::findOrFail($id);
        return response()->json($configuration);
    }

    /**
     * Création d'un nouveau réglage
     */
    public function store(Request $request)
    {
        $request->validate([
            'cle' => 'required|string|max:100|unique:configurations,cle',
            'valeur' => 'nullable|string',
            'categorie' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $configuration = Configuration::create($request->all());

        return response()->json($configuration, 201);
    }

    /**
     * Mise à jour d'un réglage existant
     */
    public function update(Request $request, $id)
    {
        $configuration = Configuration::findOrFail($id);

        $request->validate([
            'cle' => 'sometimes|string|max:100|unique:configurations,cle,' . $id . ',id_configuration',
            'valeur' => 'nullable|string',
            'categorie' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $configuration->update($request->all());

        return response()->json($configuration);
    }

    /**
     * Suppression d'un réglage
     */
    public function destroy($id)
    {
        $configuration = Configuration::findOrFail($id);
        $configuration->delete();

        return response()->json(['message' => 'Réglage supprimé']);
    }
}
