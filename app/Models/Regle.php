<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regle extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'regles';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_regle';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'actif',
        'montant_credit',
        'type_action',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'actif' => 'boolean',
        'montant_credit' => 'decimal:2',
    ];

    /**
     * Relation avec les mouvements
     * Une règle peut être utilisée par plusieurs mouvements
     */
    public function mouvements()
    {
        return $this->hasMany(Mouvement::class, 'id_regle_credit', 'id_regle');
    }

    /**
     * Scope pour récupérer seulement les règles actives
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour filtrer par type d'action
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type_action', $type);
    }
}