<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $table = 'car_models';

    protected $fillable = [
        'nom_model',
        'nb_places',
        'id_voiture',
        'id_marque',
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class, 'id_marque');
    }
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'id_voiture');
    }
}
