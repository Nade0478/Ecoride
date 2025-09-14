<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Storage;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $utilisateurs = Utilisateur::with(['role'])
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
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
            'pseudo' => 'nullable|string|max:50',
            'credit_depenser' => 'nullable|numeric|min:0',
            'credit_gagner' => 'nullable|numeric|min:0',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Gestion de l'upload de photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $utilisateur = Utilisateur::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'telephone' => $validatedData['telephone'] ?? null,
            'adresse' => $validatedData['adresse'] ?? null,
            'ville' => $validatedData['ville'] ?? null,
            'code_postal' => $validatedData['code_postal'] ?? null,
            'date_naissance' => $validatedData['date_naissance'] ?? null,
            'photo' => $photoPath,
            'pseudo' => $validatedData['pseudo'] ?? null,
            'credit_depenser' => $validatedData['credit_depenser'] ?? 0,
            'credit_gagner' => $validatedData['credit_gagner'] ?? 0,
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role_id' => $validatedData['role_id'],
        ]);

        return response()->json($utilisateur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Utilisateur $utilisateur)
    {
        return response()->json($utilisateur->load(['role']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Utilisateur $utilisateur)
    {
        $validatedData = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|nullable|string|max:20',
            'adresse' => 'sometimes|nullable|string|max:255',
            'ville' => 'sometimes|nullable|string|max:100',
            'code_postal' => 'sometimes|nullable|string|max:20',
            'date_naissance' => 'sometimes|nullable|date',
            'photo' => 'sometimes|nullable|image|max:2048',
            'pseudo' => 'sometimes|nullable|string|max:50',
            'credit_depenser' => 'sometimes|nullable|numeric|min:0',
            'credit_gagner' => 'sometimes|nullable|numeric|min:0',
            'email' => 'sometimes|required|email|unique:utilisateurs,email,' . $utilisateur->id,
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        // Mise à jour des attributs simples
        $fieldsToUpdate = [
            'nom', 'prenom', 'telephone', 'adresse', 'ville',
            'code_postal', 'date_naissance', 'pseudo',
            'credit_depenser', 'credit_gagner'
        ];

        foreach ($fieldsToUpdate as $field) {
            if (isset($validatedData[$field])) {
                $utilisateur->$field = $validatedData[$field];
            }
        }

        // Gestion spéciale de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
                Storage::disk('public')->delete($utilisateur->photo);
            }

            // Stocker la nouvelle photo
            $utilisateur->photo = $request->file('photo')->store('photos', 'public');
        }

        // Mise à jour de l'email
        if (isset($validatedData['email'])) {
            $utilisateur->email = $validatedData['email'];
        }

        // Mise à jour du mot de passe (hashé)
        if (isset($validatedData['password'])) {
            $utilisateur->password = bcrypt($validatedData['password']);
        }

        // Mise à jour du rôle
        if (isset($validatedData['role_id'])) {
            $utilisateur->role_id = $validatedData['role_id'];
        }

        $utilisateur->save();

        return response()->json($utilisateur->load(['role']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utilisateur $utilisateur)
    {
        // Supprimer la photo associée si elle existe
        if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
            Storage::disk('public')->delete($utilisateur->photo);
        }

        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur deleted successfully']);
    }
}