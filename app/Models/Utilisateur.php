<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'code_postal', // Corrigé de 'cp'
        'ville',
        'date_naissance',
        'photo',
        'pseudo',
        'credit_depenser',
        'credit_gagner',
        'role_id', // Corrigé de 'id_role'
        // Supprimé les ID étrangers qui ne devraient pas être dans fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_naissance' => 'date',
        'credit_depenser' => 'decimal:2',
        'credit_gagner' => 'decimal:2',
    ];

    public $timestamps = true;

    // Relations existantes corrigées

    // À modifier dans votre modèle Utilisateur

public function role()
{
    return $this->belongsTo(Role::class, 'role_id', 'id_role');
}

    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'id_utilisateur');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'id_utilisateur');
    }

    public function covoiturages()
    {
        return $this->hasMany(Covoiturage::class, 'id_utilisateur');
    }

    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'id_utilisateur');
    }

    // Nouvelles relations ajoutées

    /**
     * Relation avec les participations
     * Un utilisateur peut avoir plusieurs participations
     */
    public function participations()
    {
        return $this->hasMany(Participation::class, 'id_utilisateur', 'id');
    }

    /**
     * Relation avec les mouvements (renommée pour plus de clarté)
     * Un utilisateur peut avoir plusieurs mouvements
     */
    public function mouvements()
    {
        return $this->hasMany(Mouvement::class, 'id_utilisateur', 'id');
    }

    /**
     * Relation many-to-many avec les covoiturages via participations
     */
    public function covoituragesParticipes()
    {
        return $this->belongsToMany(
            Covoiturage::class,
            'participations',
            'id_utilisateur',
            'id_covoiturage'
        )->withPivot(['date_inscription', 'date_validation', 'statut', 'presente'])
         ->withTimestamps();
    }

    // Accesseurs et méthodes utilitaires

    /**
     * Calculer le solde de crédit de l'utilisateur
     */
    public function getSoldeCreditsAttribute()
    {
        $credits = $this->mouvements()->where('type_mouvement', 'credit')->sum('montant');
        $debits = $this->mouvements()->where('type_mouvement', 'debit')->sum('montant');

        return $credits - $debits;
    }

    /**
     * Calculer le crédit total (méthode alternative basée sur les champs existants)
     */
    public function getCreditTotalAttribute()
    {
        return $this->credit_gagner - $this->credit_depenser;
    }

    /**
     * Obtenir le nom complet
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Vérifier si l'utilisateur a une photo
     */
    public function hasPhoto()
    {
        return !empty($this->photo);
    }

    /**
     * Obtenir l'URL de la photo
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    // Scopes utiles

    /**
     * Scope pour rechercher par nom ou prénom
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nom', 'like', '%' . $term . '%')
              ->orWhere('prenom', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%');
        });
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }
}