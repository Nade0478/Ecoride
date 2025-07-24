<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur; // Assurez-vous d'importer le modèle Utilisateur

class UtilisateurController extends Controller // Changé en `UtilisateurController` pour suivre les conventions de nommage
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Utilisation de l'alias Utilisateur
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
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20', 
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'photo' => 'nullable|image|max:2048', // Ajout de la validation pour la photo
            'pseudo' => 'nullable|string|max:50',
            'credit_depenser' => 'nullable|numeric|min:0', 
            'credit_gagner' => 'nullable|numeric|min:0', 
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Création via Utilisateur
        $utilisateur = Utilisateur::create([
            'name' => $validatedData['name'],
            'prenom' => $validatedData['prenom'],
            'telephone' => $validatedData['telephone'] ?? null,
            'adresse' => $validatedData['adresse'] ?? null,
            'ville' => $validatedData['ville'] ?? null,
            'code_postal' => $validatedData['code_postal'] ?? null,
            'date_naissance' => $validatedData['date_naissance'] ?? null,
            'photo' => $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null, // Stockage de la photo
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
        return response()->json($utilisateur->load(['role', 'transactionsStripe']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Utilisateur $utilisateur)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|nullable|string|max:20', // Changé en `telephone`
            'adresse' => 'sometimes|nullable|string|max:255',
            'ville' => 'sometimes|nullable|string|max:100',
            'code_postal' => 'sometimes|nullable|string|max:20',
            'date_naissance' => 'sometimes|nullable|date',
            'photo' => 'sometimes|nullable|image|max:2048', // Ajout de la validation pour la photo
            'pseudo' => 'sometimes|nullable|string|max:50',
            'credit_depenser' => 'sometimes|nullable|numeric|min:0', // Changé en `credit_depenser`
            'credit_gagner' => 'sometimes|nullable|numeric|min:0', // Changé en `credit_gagner`
            'email' => 'sometimes|required|email|unique:utilisateurs,email,' . $utilisateur->id, // Exclusion de l'email actuel (ajout d'une virgule après `$utilisateur->id`)
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|required|exists:roles,id',
  ]);

  // Mise à jour des attributs
  foreach (['name', 'prenom', 'telephone', 'adresse', 'ville', 'code_postal', 'date_naissance', 'pseudo', 'credit_depenser', 'credit_gagner'] as $field) {
    if (isset($validatedData[$field])) {
      $utilisateur->$field = $validatedData[$field];
    }
  }

  // Gestion de la mise à jour de la photo
  if (isset($validatedData['photo'])) {
    $utilisateur->photo = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;
  }

  // Mise à jour de l'email, du mot de passe et du rôle
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
 public function destroy(Utilisateur $utilisateur)
 {
  $utilisateur->delete();

  return response()->json(['message' => 'Utilisateur deleted successfully'], 204);
 }
}
