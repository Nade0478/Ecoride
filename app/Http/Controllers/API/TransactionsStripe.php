<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// 🟨 Alias ajouté pour le modèle TransactionsStripe
use App\Models\TransactionsStripe as TransactionsStripe;

class TransactionsStripe extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🟨 Utilisation de TransactionsStripe
        $transactions = TransactionsStripe::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'status' => 'required|string|max:20',
            // Add other fields as necessary
        ]);

        // 🟨 Création via TransactionsStripeModel
        $transaction = TransactionsStripe::create($validatedData);

        return response()->json($transaction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionsStripe $transactionsStripe) // 🟨 Alias dans la signature
    {
        return response()->json($transactionsStripe->load(['user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionsStripe $transactionsStripe) // 🟨 Alias dans la signature
    {
        $validatedData = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'currency' => 'sometimes|required|string|max:3',
            'status' => 'sometimes|required|string|max:20',
            // Add other fields as necessary
        ]);

        $transactionsStripe->update($validatedData);

        return response()->json($transactionsStripe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionsStripe $transactionsStripe) // 🟨 Alias dans la signature
    {
        $transactionsStripe->delete();
        return response()->json(null, 204);
    }
}
