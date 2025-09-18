<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Covoiturage extends Model
{
    protected $table = 'covoiturage';

    protected $fillable = [
        'date_depart',
        'date_arrivee',
        'heure_depart',
        'heure_arrivee',
        'lieu_depart',
        'lieu_arrivee',
        'statut',
        'nombre_place',
        'prix_credit',
        'ecologique',
        'accepte_fumeur',
        'accepte_animal',
        'id_gestionValidation',
        'id_user',
        'id_voiture',
        'id_avis',
    ];

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }
    public function gestionValidation()
    {
        return $this->hasOne(GestionValidation::class, 'id_covoiturage');
    }
    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_covoiturage');
    }

}
