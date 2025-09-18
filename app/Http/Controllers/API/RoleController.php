<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller  // Nom correct : RoleController, pas Role
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::orderBy('created_at', 'desc')->get();
        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_role' => 'required|string|max:255|unique:roles,nom_role',
            'permissions' => 'required|array',
        ]);

        $role = Role::create($validatedData);

        return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json($role->load('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validatedData = $request->validate([
            'nom_role' => 'sometimes|required|string|max:255|unique:roles,nom_role,' . $role->id_role . ',id_role',
            'permissions' => 'sometimes|required|array',
        ]);

        $role->update($validatedData);

        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Rôle supprimé avec succès']);
    }

    /**
     * Get roles by name
     */
    public function byName($name)
    {
        $role = Role::byName($name)->first();

        if (!$role) {
            return response()->json(['message' => 'Rôle non trouvé'], 404);
        }

        return response()->json($role);
    }

    /**
     * Get admin roles
     */
    public function admins()
    {
        $adminRoles = Role::admin()->get();
        return response()->json($adminRoles);
    }
}