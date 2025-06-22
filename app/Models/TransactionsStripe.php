<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsStripe extends Model
{
    protected $table = 'transactions_stripe';

    protected $fillable = [
        'utilisateur_id', 'montant_euros', 'date_paiement',
        'status', 'montant_credits', 'stripe_payment_id'
    ];
}

