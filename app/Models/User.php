<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'date_naissance',
        'photo',
        'pseudo',
        'credit_depenser',
        'credit_gagner',
        'email',
        'password',
        'id_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance' => 'date',
        'credit_depenser' => 'decimal:2',
        'credit_gagner' => 'decimal:2',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }
}
