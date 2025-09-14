<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'mouvements';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_mouvement';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type_mouvement',
        'montant',
        'date_operation',
        'description',
        'id_regle_credit',
        'id_covoiturage',
        'id_utilisateur',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'date_operation' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     * Un mouvement appartient à un utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id');
    }

    /**
     * Relation avec la règle de crédit
     * Un mouvement peut être lié à une règle
     */
    public function regle()
    {
        return $this->belongsTo(Regle::class, 'id_regle_credit', 'id_regle');
    }

    /**
     * Relation avec le covoiturage (optionnelle)
     * Un mouvement peut être lié à un covoiturage
     */
    public function covoiturage()
    {
        return $this->belongsTo(Covoiturage::class, 'id_covoiturage', 'id_covoiturage');
    }

    /**
     * Scope pour récupérer les crédits
     */
    public function scopeCredit($query)
    {
        return $query->where('type_mouvement', 'credit');
    }

    /**
     * Scope pour récupérer les débits
     */
    public function scopeDebit($query)
    {
        return $query->where('type_mouvement', 'debit');
    }

    /**
     * Scope pour récupérer les mouvements par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type_mouvement', $type);
    }

    /**
     * Scope pour récupérer les mouvements d'une période
     */
    public function scopeBetweenDates($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_operation', [$dateDebut, $dateFin]);
    }

    /**
     * Accesseur pour formater le montant avec le signe
     */
    public function getMontantFormatteAttribute()
    {
        $signe = $this->type_mouvement === 'credit' ? '+' : '-';
        return $signe . number_format($this->montant, 2) . ' €';
    }

    /**
     * Mutateur pour définir automatiquement la date d'opération
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mouvement) {
            if (!$mouvement->date_operation) {
                $mouvement->date_operation = now();
            }
        });
    }
}