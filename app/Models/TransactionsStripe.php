<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsStripe extends Model
{
    protected $table = 'transactions_stripe';

    protected $fillable = [
        'id_transactions_stripe',
        'montant_euros',
        'date_paiement',
        'status',
        'montant_credits',
        'id_utilisateur',
    ];
}

