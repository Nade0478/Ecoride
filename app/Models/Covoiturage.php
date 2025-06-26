<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Covoiturage extends Model
{
    protected $table = 'covoiturage';

    protected $fillable = [
        'id_covoiturage',
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
        'id_utilisateur',
        'id_voiture',
    ];

    /**
     * Relation avec l'user (conducteur)
     */
    public function user()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    /**
     * Relation avec les avis associés au covoiturage
     */
    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_covoiturage');
    }

    /**
     * Relation avec la voiture utilisée pour le trajet
     */
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }
}
