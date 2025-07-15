<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'cp',
        'ville',
        'date_naissance',
        'photo',
        'pseudo',
        'credit_depenser',
        'credit_gagner',
        'credit_total',
        'id_role',
        'id_mouvementCredit',
        'id_transactionStripe',
        'id_configuration',
        'id_avis',
        'id_covoiturage',
        'id_voiture'
    ];

    public $timestamps = true;

    // Relations

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'id_utilisateur');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_utilisateur');
    }

    public function covoiturages()
    {
        return $this->hasMany(Covoiturage::class, 'id_utilisateur');
    }

    public function mouvementsCredits()
    {
        return $this->hasMany(MouvementCredit::class, 'id_utilisateur');
    }

    public function transactionsStripe()
    {
        return $this->hasMany(TransactionStripe::class, 'id_utilisateur');
    }

    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'id_utilisateur');
    }
}
