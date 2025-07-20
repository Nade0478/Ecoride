<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Ajout d'un alias pour le modèle CarModel
use App\Models\CarModel as CarModelModel;

class CarModel extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de CarModelModel à la place de \App\Models\CarModel
        $carModels = CarModelModel::with(['brand', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($carModels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // 'brand_id' => 'required|exists:brands,id',
            // 'category_id' => 'required|exists:categories,id',
        ]);

        // 🟨 Création d'un nouveau modèle avec l'alias CarModelModel
        $carModel = CarModelModel::create($validatedData);

        return response()->json($carModel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CarModelModel $carModel)
    {
        return response()->json($carModel->load(['brand', 'category']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarModelModel $carModel)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            // 'brand_id' => 'sometimes|required|exists:brands,id',
            // 'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $carModel->update($validatedData);

        return response()->json($carModel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarModelModel $carModel)
    {
        $carModel->delete();

        return response()->json(['message' => 'Car model deleted successfully'], 204);
    }
}
