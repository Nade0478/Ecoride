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
    public function marque()
    {
        return $this->belongsTo(Marque::class, 'marque_id');
    }
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'carModel_id');
    }
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
    public function covoiturages()
    {
        return $this->hasMany(Covoiturage::class, 'voiture_id');
    }
    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_covoiturage');
    }
    public function scopeWithMarqueAndModel($query)
    {
        return $query->with(['marque', 'carModel']);
    }
    public function scopeWithUtilisateur($query)
    {
        return $query->with('utilisateur');
    }
    public function scopeWithCovoiturages($query)
    {
        return $query->with('covoiturages');
    }
    public function scopeWithAvis($query)
    {
        return $query->with('avis');
    }
    public function scopeWithAllRelations($query)
    {
        return $query->with(['marque', 'carModel', 'utilisateur', 'covoiturages', 'avis']);
    }
    public function scopeWithAllRelationsAndCount($query)
    {
        return $query->withCount(['covoiturages', 'avis'])
                     ->with(['marque', 'carModel', 'utilisateur']);
    }
}
