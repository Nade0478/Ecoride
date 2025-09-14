<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateur;
use App\Models\Mouvement;

class GestionValidation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'GestionValidations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'valider',
        'id_covoiturage',
        'id_mouvement',
    ];
// Relation

    public function mouvement()
    {
        return $this->belongsTo(Mouvement::class, 'id_mouvement');
    }

    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage');
    }
}

