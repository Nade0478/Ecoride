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
        'nmarque',
        'nb_places',
        'type',
        'id_carModel',
    ];

    /**
     * Get the carModel that owns the Marque.
     */
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'id_carModel');
    }
}
