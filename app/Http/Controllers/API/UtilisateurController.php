<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $utilisateurs = Utilisateur::with('role')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $utilisateurs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs',
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
                'id_role' => 'required|exists:roles,id',
            ]);

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
                'password' => Hash::make($validatedData['password']),
                'id_role' => $validatedData['id_role'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => $utilisateur->load('role')
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
            $utilisateur = Utilisateur::with('role')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $utilisateur
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
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
            $utilisateur = Utilisateur::findOrFail($id);

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
                'id_role' => 'sometimes|required|exists:roles,id',
            ]);

            foreach ($validatedData as $key => $value) {
                if ($key === 'password') {
                    $utilisateur->password = Hash::make($value);
                } elseif ($key === 'photo') {
                    if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
                        Storage::disk('public')->delete($utilisateur->photo);
                    }
                    $utilisateur->photo = $request->file('photo')->store('photos', 'public');
                } else {
                    $utilisateur->$key = $value;
                }
            }

            $utilisateur->save();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => $utilisateur->load('role')
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
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
            $utilisateur = Utilisateur::findOrFail($id);

            if ($utilisateur->photo && Storage::disk('public')->exists($utilisateur->photo)) {
                Storage::disk('public')->delete($utilisateur->photo);
            }

            $utilisateur->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
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
