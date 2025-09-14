<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'participations';

    /**
     * The primary key associated with the table.
     * Clé primaire composite
     */
    protected $primaryKey = ['id_covoiturage', 'id_utilisateur'];

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_covoiturage',
        'id_utilisateur',
        'date_inscription',
        'date_validation',
        'statut',
        'presente',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_inscription' => 'datetime',
        'date_validation' => 'datetime',
        'presente' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     * Une participation appartient à un utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id');
    }

    /**
     * Relation avec le covoiturage
     * Une participation appartient à un covoiturage
     */
    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage', 'id_covoiturage');
    }

    /**
     * Scope pour récupérer les participations validées
     */
    public function scopeValidated($query)
    {
        return $query->whereNotNull('date_validation');
    }

    /**
     * Scope pour récupérer les participations par statut
     */
    public function scopeByStatus($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour récupérer les participations présentes
     */
    public function scopePresent($query)
    {
        return $query->where('presente', true);
    }

    /**
     * Mutateur pour définir automatiquement la date d'inscription
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($participation) {
            if (!$participation->date_inscription) {
                $participation->date_inscription = now();
            }
        });
    }
}