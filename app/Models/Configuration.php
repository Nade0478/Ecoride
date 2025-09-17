<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Configuration extends Model
{
    protected $table = 'configurations';
    protected $primaryKey = 'id_conf';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cle', 'valeur', 'categorie', 'description',
        'type_donnee', 'modifiable_interface', 'cache_requis'
    ];

    protected $guarded = ['id_conf', 'created_at', 'updated_at'];

    protected function casts(): array
    {
        return [
            'modifiable_interface' => 'boolean',
            'cache_requis' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // === SCOPES LOCAUX POUR REQUÊTES FRÉQUENTES ===

    public function scopeParCategorie(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeModifiables(Builder $query): Builder
    {
        return $query->where('modifiable_interface', true);
    }

    public function scopeRequierentCache(Builder $query): Builder
    {
        return $query->where('cache_requis', true);
    }

    public function scopeParCle(Builder $query, string $cle): Builder
    {
        return $query->where('cle', 'like', "%{$cle}%");
    }

    public function scopeCategorieTriee(Builder $query, string $categorie): Builder
    {
        return $query->where('categorie', $categorie)->orderBy('cle');
    }

    public function scopeRecemmentModifiees(Builder $query, int $heures = 24): Builder
    {
        return $query->where('updated_at', '>=', now()->subHours($heures))
                    ->orderByDesc('updated_at');
    }

    // === ACCESSORS & MUTATORS (Laravel 10/11) ===

    protected function valeur(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $typeDonnee = $attributes['type_donnee'] ?? 'string';

                return match ($typeDonnee) {
                    'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    'integer' => (int) $value,
                    'float' => (float) $value,
                    'json', 'array' => is_string($value) ? json_decode($value, true) : $value,
                    default => (string) $value,
                };
            },
            set: function (mixed $value) {
                if (is_array($value) || is_object($value)) {
                    return json_encode($value);
                }
                return $value;
            }
        );
    }

    protected function cleFormatee(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) =>
                ucwords(str_replace('_', ' ', $attributes['cle']))
        );
    }

    // === MÉTHODES MÉTIER SPÉCIFIQUES AU COVOITURAGE ===

    public static function obtenirValeur(string $cle, mixed $defaut = null): mixed
    {
        $config = Cache::remember(
            "config.{$cle}",
            now()->addHours(1),
            fn () => self::where('cle', $cle)->first()
        );

        return $config?->valeur ?? $defaut;
    }

    public static function definirValeur(string $cle, mixed $valeur): bool
    {
        $config = self::where('cle', $cle)->first();

        if (!$config) return false;

        $config->valeur = $valeur;
        $resultat = $config->save();

        if ($config->cache_requis) {
            Cache::forget("config.{$cle}");
        }

        return $resultat;
    }

    public static function obtenirCategorie(string $categorie): array
    {
        return Cache::remember(
            "configs.categorie.{$categorie}",
            now()->addMinutes(30),
            fn () => self::parCategorie($categorie)
                          ->pluck('valeur', 'cle')
                          ->toArray()
        );
    }

    public static function obtenirTarifKm(): float
    {
        return (float) self::obtenirValeur('tarif_base_km', 0.25);
    }

    // === ÉVÉNEMENTS DU MODÈLE ===

    protected static function booted(): void
    {
        static::updated(function (Configuration $config) {
            if ($config->cache_requis) {
                Cache::forget("config.{$config->cle}");
                Cache::forget("configs.categorie.{$config->categorie}");
            }
        });

        static::deleted(function (Configuration $config) {
            Cache::forget("config.{$config->cle}");
            Cache::forget("configs.categorie.{$config->categorie}");
        });

        static::saving(function (Configuration $config) {
            $config->cle = strtolower(trim($config->cle));

            $typesValides = ['string', 'integer', 'float', 'boolean', 'json', 'array'];
            if (!in_array($config->type_donnee, $typesValides)) {
                $config->type_donnee = 'string';
            }
        });
    }

    public static function obtenirCategories(): array
    {
        return [
            'general' => 'Paramètres généraux',
            'tarifs' => 'Gestion des tarifs et prix',
            'notifications' => 'Configuration des notifications',
            'systeme' => 'Paramètres système',
            'covoiturage' => 'Règles de covoiturage',
            'paiement' => 'Paramètres de paiement',
            'securite' => 'Configuration sécurité'
        ];
    }

    public function validerValeur(mixed $valeur): bool
    {
        return match ($this->type_donnee) {
            'boolean' => is_bool($valeur) || in_array($valeur, [0, 1, '0', '1', 'true', 'false']),
            'integer' => is_numeric($valeur) && is_int((int) $valeur),
            'float' => is_numeric($valeur),
            'json', 'array' => is_array($valeur) || (is_string($valeur) && json_decode($valeur) !== null),
            default => true,
        };
    }

    public function scopePourAdmin(Builder $query): Builder
    {
        return $query->where('modifiable_interface', true)
                    ->orderBy('categorie')
                    ->orderBy('cle');
    }
}