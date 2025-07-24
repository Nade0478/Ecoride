<?php

namespace App\Models;


use App\Http\Controllers\API\Utilisateur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStripe extends Model
{
    use HasFactory;

    protected $table = 'transactions_stripe';

    protected $primaryKey = 'id_transaction';

    protected $fillable = [
        'montant_euros',
        'date_paiement',
        'status',
        'montant_credits',
        'id_utilisateur',
        'id_mouvementCredit'
    ];
// relations

    public function utilisateur()
    {
        return $this->belongsTo(\App\Models\Utilisateur::class, 'id_utilisateur');
    }
    public function mouvementCredit()
    {
        return $this->hasOne(MouvementCredit::class, 'id_mouvementCredit');
    }
}