<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'libelle',
    ];

    /**
     * Get the voitures for the marque.
     */
    public function voitures()
    {
        return $this->hasMany(Voiture::class);
    }
}
