<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mouvement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MouvementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mouvement::with(['user', 'regle', 'covoiturage']);

        // Filtres optionnels
        if ($request->has('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        if ($request->has('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        if ($request->has('date_debut') && $request->has('date_fin')) {
            $query->betweenDates($request->date_debut, $request->date_fin);
        }

        $mouvements = $query->orderBy('date_operation', 'desc')->get();

        return response()->json($mouvements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_mouvement' => 'required|string|in:credit,debit',
            'montant' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'id_regle_credit' => 'nullable|exists:regles,id_regle',
            'id_covoiturage' => 'nullable|exists:covoiturages,id_covoiturage',
            'id_user' => 'required|exists:id_users',
        ]);

        $mouvement = Mouvement::create($validatedData);

        return response()->json($mouvement->load(['user', 'regle', 'covoiturage']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Mouvement $mouvement)
    {
        return response()->json($mouvement->load(['user', 'regle', 'covoiturage']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mouvement $mouvement)
    {
        $validatedData = $request->validate([
            'type_mouvement' => 'sometimes|required|string|in:credit,debit',
            'montant' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|nullable|string',
            'id_regle_credit' => 'sometimes|nullable|exists:regles,id_regle',
            'id_covoiturage' => 'sometimes|nullable|exists:covoiturages,id_covoiturage',
        ]);

        $mouvement->update($validatedData);

        return response()->json($mouvement->load(['user', 'regle', 'covoiturage']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mouvement $mouvement)
    {
        $mouvement->delete();

        return response()->json(['message' => 'Mouvement supprimé avec succès']);
    }

    /**
     * Get movements by user
     */
    public function getMouvementsByUser($Iduser)
    {
        $mouvements = Mouvement::where('id_user', $Iduser)
            ->with(['regle', 'covoiturage'])
            ->orderBy('date_operation', 'desc')
            ->get();

        return response()->json($mouvements);
    }

    /**
     * Get credit movements
     */
    public function credits()
    {
        $mouvements = Mouvement::credit()
            ->with(['user', 'regle'])
            ->orderBy('date_operation', 'desc')
            ->get();

        return response()->json($mouvements);
    }

    /**
     * Get debit movements
     */
    public function debits()
    {
        $mouvements = Mouvement::debit()
            ->with(['user', 'regle'])
            ->orderBy('date_operation', 'desc')
            ->get();

        return response()->json($mouvements);
    }

    /**
     * Get user balance
     */
    public function soldeUser($Iduser)
    {
        $credits = Mouvement::where('id_user', $Iduser)
            ->where('type_mouvement', 'credit')
            ->sum('montant');

        $debits = Mouvement::where('id_user', $Iduser)
            ->where('type_mouvement', 'debit')
            ->sum('montant');

        $solde = $credits - $debits;

        return response()->json([
            'id_user' => $Iduser,
            'credits' => $credits,
            'debits' => $debits,
            'solde' => $solde
        ]);
    }

    /**
     * Get monthly report
     */
    public function rapportMensuel($annee, $mois)
    {
        $debut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $fin = Carbon::create($annee, $mois, 1)->endOfMonth();

        $mouvements = Mouvement::betweenDates($debut, $fin)
            ->with(['user', 'regle'])
            ->get();

        $totalCredits = $mouvements->where('type_mouvement', 'credit')->sum('montant');
        $totalDebits = $mouvements->where('type_mouvement', 'debit')->sum('montant');

        return response()->json([
            'periode' => $debut->format('Y-m'),
            'total_credits' => $totalCredits,
            'total_debits' => $totalDebits,
            'nombre_mouvements' => $mouvements->count(),
            'mouvements' => $mouvements
        ]);
    }
}