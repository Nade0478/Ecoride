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
    /**
     * Get the utilisateur that owns the MouvementCredit.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
    /**
     * Get the gestionValidation that owns the MouvementCredit.
     */
    public function gestionValidation()
    {
        return $this->belongsTo(GestionValidation::class, 'id_gestionValidation');
    }

}
