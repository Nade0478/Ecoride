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
    protected $table = 'mouvement_credits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'id_user',
        'id_gestion_validation',
        'type_mouvement',
        'date_operation',
        'montant',
    ];
}
