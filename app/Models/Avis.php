<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'note',
        'commentaire',
        'statut',
        'id_covoiturage',
        'id_utilisateur'
    ];
 // Relations

    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage');
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
}
