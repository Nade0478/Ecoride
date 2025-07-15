<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateur;
use App\Models\MouvementCredit;

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
        'id_mouvementCredit',
    ];
// Relation

    public function mouvementCredit()
    {
        return $this->belongsTo(MouvementCredit::class, 'id_mouvementCredit');
    }

    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage');
    }
}

