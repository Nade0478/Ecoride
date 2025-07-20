<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class Utilisateur extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de l'alias Utilisateur
        $utilisateurs = Utilisateur::with(['role', 'transactionsStripe'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($utilisateurs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        // 🟨 Création via Utilisateur
        $utilisateur = Utilisateur::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role_id' => $validatedData['role_id'],
        ]);

        return response()->json($utilisateur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Utilisateur $utilisateur) // 🟨 Alias dans la signature
    {
        return response()->json($utilisateur->load(['role', 'transactionsStripe']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Utilisateur $utilisateur) // 🟨 Alias dans la signature
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:utilisateurs,email,' . $utilisateur->id,
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        if (isset($validatedData['name'])) {
            $utilisateur->name = $validatedData['name'];
        }
        if (isset($validatedData['email'])) {
            $utilisateur->email = $validatedData['email'];
        }
        if (isset($validatedData['password'])) {
            $utilisateur->password = bcrypt($validatedData['password']);
        }
        if (isset($validatedData['role_id'])) {
            $utilisateur->role_id = $validatedData['role_id'];
        }

        $utilisateur->save();

        return response()->json($utilisateur);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utilisateur $utilisateur) // 🟨 Alias dans la signature
    {
        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur deleted successfully'], 204);
    }
}
