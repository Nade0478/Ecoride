<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    protected $table = 'voitures';

    protected $fillable = [
        'marque_id',
        'carModel_id',
        'date-mise-en-circulation',
        'couleur',
        'immatriculation',
        'energie',
        'utilisateur_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function marque()
    {
        return $this->belongsTo(Marque::class, 'marque_id');
    }
    public function modele()
    {
        return $this->belongsTo(CarModel::class, 'carModel_id');
    }
}
