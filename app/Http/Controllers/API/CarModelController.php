<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarModel;

class CarModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $carModels = CarModel::with(['marque']) // Selon votre MCD : marque au lieu de brand
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $carModels
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des modèles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation selon votre MCD
            $validatedData = $request->validate([
                'nom_modele' => 'required|string|max:255',
                'nb_places' => 'required|integer|min:1|max:9', // Limite réaliste
                'id_marque' => 'required|exists:marque,id_marque', // Selon votre MCD
            ]);

            $carModel = CarModel::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Modèle créé avec succès',
                'data' => $carModel->load('marque')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $carModel = CarModel::with(['marque'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $carModel
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Modèle non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $carModel = CarModel::findOrFail($id);

            $validatedData = $request->validate([
                'nom_modele' => 'sometimes|required|string|max:255',
                'nb_places' => 'sometimes|required|integer|min:1|max:9',
                'id_marque' => 'sometimes|required|exists:marque,id_marque',
            ]);

            $carModel->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Modèle mis à jour avec succès',
                'data' => $carModel->load('marque')
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Modèle non trouvé'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $carModel = CarModel::findOrFail($id);
            $carModel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Modèle supprimé avec succès'
            ], 200); // 200 au lieu de 204 pour inclure le message
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Modèle non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}