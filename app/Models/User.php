<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'telephone',
        'adresse', 'cp', 'ville', 'date_naissance', 'photo',
        'pseudo', 'credit_depenser', 'credit_gagner'
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'user_id');
    }
}