<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->user = new User();
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|min:2|max:50',
            'prenom' => 'required|string|min:2|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:255',
            'pseudo' => 'required|string|min:3|max:50|unique:users',
        ]);

        // Récupérer le rôle "passager" par défaut
        $passagerRole = Role::where('nom_role', 'passager')->first();

        if (!$passagerRole) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Erreur système: rôle passager introuvable',
                ],
            ], 500);
        }

        $user = User::create([
            'nom' => $request['nom'],
            'prenom' => $request['prenom'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'pseudo' => $request['pseudo'],
            'credits' => 20, // Crédits initiaux EcoRide
            'id_role' => $passagerRole->id_role,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Compte EcoRide créé avec succès!',
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                ],
            ],
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'meta' => [
                    'code' => 401,
                    'status' => 'error',
                    'message' => 'Email ou mot de passe incorrect.',
                ],
            ], 401);
        }

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Connexion réussie.',
            ],
            'data' => [
                'user' => JWTAuth::user(),
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                ],
            ],
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Déconnexion réussie',
                ],
                'data' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'status' => 'error',
                    'message' => 'Erreur lors de la déconnexion',
                ],
            ], 500);
        }
    }

    public function me()
    {
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Profil utilisateur récupéré',
            ],
            'data' => [
                'user' => JWTAuth::user(),
            ],
        ]);
    }
}
