<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parametres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id_parametre',
        'propriete',
        'valeur',
    ];
}
