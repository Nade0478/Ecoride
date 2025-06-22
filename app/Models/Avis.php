<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'id_avis',
        'id_user',
        'note',
        'commentaire',
        'statut',
        'id_covoiturage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage');
    }
}
