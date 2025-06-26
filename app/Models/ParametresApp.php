<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametresApp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parametresApps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id_parametresApp',
        'propriete',
        'valeur',
    ];
}
