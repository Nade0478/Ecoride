<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Utilisateur;
use App\Models\MouvementCredit;

class GestionValidation extends Model
{
    // Si la clé primaire ne s'appelle pas "id", on l'indique ici
    protected $primaryKey = 'id_gestion_validation';

    // Nom de la table dans la base de données
    protected $table = 'gestion_validations';

    // Attributs pouvant être remplis en masse
    protected $fillable = [
        'id_gestion_validation',
        'id_utilisateur',
        'id_mouvement_credit',
        'type_mouvement',
        'date_operation',
        'montant',
    ];

    /**
     * Relation avec l'utilisateur qui a effectué la validation.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    /**
     * Relation avec le mouvement de crédit validé.
     */
    public function mouvementCredit()
    {
        return $this->belongsTo(MouvementCredit::class, 'id_MouvementCredit');
    }
}
