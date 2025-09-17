<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marque as MarqueModel;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MarqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $marques = MarqueModel::with(['carModel'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $marques
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des marques',
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
            $validatedData = $request->validate([
                'nmarque' => 'required|string|max:255',
                'nb_places' => 'required|integer|min:1|max:9',
                'type' => 'required|string|max:50',
                'id_carModel' => 'required|exists:car_models,id', // ✅ correction ici
            ]);

            $marque = MarqueModel::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Marque créée avec succès',
                'data' => $marque->load('carModel')
            ], 201);
        } catch (ValidationException $e) {
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
            $marque = MarqueModel::with(['carModel'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $marque
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Marque non trouvée'
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
            $marque = MarqueModel::findOrFail($id);

            $validatedData = $request->validate([
                'nmarque' => 'sometimes|required|string|max:255',
                'nb_places' => 'sometimes|required|integer|min:1|max:9',
                'type' => 'sometimes|required|string|max:50',
                'id_carModel' => 'sometimes|required|exists:car_models,id', // ✅ correction ici
            ]);

            $marque->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Marque mise à jour avec succès',
                'data' => $marque->load('carModel')
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Marque non trouvée'
            ], 404);
        } catch (ValidationException $e) {
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
            $marque = MarqueModel::findOrFail($id);
            $marque->delete();

            return response()->json([
                'success' => true,
                'message' => 'Marque supprimée avec succès'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Marque non trouvée'
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
