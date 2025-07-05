<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementCredit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MouvementCredits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id_mouvementCredit',
        'id_utilisateur',
        'id_gestionValidation',
        'type_mouvement',
        'date_operation',
        'montant',
    ];
}
