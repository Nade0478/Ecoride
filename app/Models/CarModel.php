<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $table = 'car_models';

    protected $fillable = [
        'id_car_model',
        'nom_model',
        'nb_places',
        'marque_id',
        'voiture_id'
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class, 'marque_id');
    }
    public function voiture()
    {
        return $this->belongsTo(Voiture::class, 'voiture_id');
    }
}
