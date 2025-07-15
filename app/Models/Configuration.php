<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Configuration extends Model
{
    use HasFactory;

    // Nom de la table explicitement indiqué si tu ne suis pas les conventions Laravel
    protected $table = 'configurations';

    // Clé primaire personnalisée
    protected $primaryKey = 'id_configuration';

    // Champs modifiables
    protected $fillable = [
        'cle',
        'valeur',
        'categorie',
        'description',
        'id_utilisateur'
    ];

    // Cast pour que les valeurs soient correctement interprétées
    protected $casts = [
        'valeur' => 'string',
    ];

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour récupérer un paramètre par sa clé
     */
    public function scopeCle($query, $cle)
    {
        return $query->where('cle', $cle)->first();
    }

    /**
     * Accesseur personnalisé pour lire la valeur d'une clé
     */
    public static function getValeur($cle)
    {
        return optional(self::where('cle', $cle)->first())->valeur;
    }

    // Relation
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }
    /**
     * Scope pour récupérer les configurations par utilisateur
     */
    public function scopeParUtilisateur($query, $id_utilisateur)
    {
        return $query->where('id_utilisateur', $id_utilisateur);
    }
}
