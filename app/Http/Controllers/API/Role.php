<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Alias ajouté pour le modèle Role
use App\Models\Role as RoleModel;

class Role extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de RoleModel pour charger les rôles
        $roles = RoleModel::with(['permissions'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_role' => 'required|string|max:255',
            'permissions' => 'array',
            'niveau_acceptation' => 'required|integer|min:1|max:5',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // 🟨 Création via RoleModel
        $role = RoleModel::create([
            'name' => $validatedData['name'],
        ]);

        if (isset($validatedData['permissions'])) {
            $role->permissions()->attach($validatedData['permissions']);
        }

        return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoleModel $role) // 🟨 Alias dans la signature
    {
        return response()->json($role->load(['permissions']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoleModel $role) // 🟨 Alias dans la signature
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (isset($validatedData['name'])) {
            $role->name = $validatedData['name'];
        }

        if (isset($validatedData['permissions'])) {
            $role->permissions()->sync($validatedData['permissions']);
        }

        $role->save();

        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoleModel $role) // 🟨 Alias dans la signature
    {
        $role->permissions()->detach();
        $role->delete();

        return response()->json(null, 204);
    }
}
