<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionStripe;
use Illuminate\Support\Facades\Http;

class TransactionsStripeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = TransactionStripe::with(['user'])
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
        ]);

        $transaction = TransactionStripe::create($validatedData);

        return response()->json($transaction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionStripe $transactionsStripe)
    {
        return response()->json($transactionsStripe->load(['user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionStripe $transactionsStripe)
    {
        $validatedData = $request->validate([
            'amount' => 'sometimes|required|numeric',
            'currency' => 'sometimes|required|string|max:3',
            'status' => 'sometimes|required|string|max:20',
        ]);

        $transactionsStripe->update($validatedData);

        return response()->json($transactionsStripe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionStripe $transactionsStripe)
    {
        $transactionsStripe->delete();

        return response()->json(null, 204);
    }

    /**
     * Create Stripe payment intent.
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|string|max:3',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $request->amount * 100, // montant en centimes
                'currency' => $request->currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $intent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
