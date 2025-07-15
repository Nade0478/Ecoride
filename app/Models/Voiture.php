<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    protected $table = 'voiture';

    protected $primaryKey = 'id_voiture'; // ✅ Correspond à la clé primaire dans le schéma

    public $timestamps = true;

    protected $fillable = [
        'date_mise_en_circulation',
        'couleur',
        'immatriculation',
        'energie',
        'id_covoiturage',
        'id_carModel',
        'id_utilisateur'
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'id_carModel');
    }

    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage');
    }
}
