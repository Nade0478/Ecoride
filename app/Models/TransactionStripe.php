<?php

namespace App\Models;


use App\Http\Controllers\API\Utilisateur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStripe extends Model
{
    use HasFactory;

    protected $table = 'transactions_stripe';

    protected $primaryKey = 'id_transaction'; // 🔹 Doit correspondre à la migration

    protected $fillable = [
        'montant_euros',
        'date_paiement',
        'status',
        'montant_credits',
        'stripe_payment_id',
        'id_utilisateur',
    ];

    // Relation avec l’utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
}
