<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method \App\Models\User|null user()
 */
class StoreConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les admins peuvent créer des configurations
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'cle' => [
                'required', 'string', 'max:100',
                Rule::unique('configurations', 'cle')
            ],
            'valeur' => 'required',
            'categorie' => [
                'required', 'string',
                Rule::in(['general', 'covoiturage', 'moderation', 'dashboard', 'securite', 'notifications', 'paiement'])
            ],
            'description' => 'nullable|string|max:500',
            'type_donnee' => [
                'required', 'string',
                Rule::in(['string', 'integer', 'float', 'boolean', 'json'])
            ],
            'modifiable_interface' => 'boolean',
            'cache_requis' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'cle.required' => 'La clé est obligatoire.',
            'cle.unique' => 'Cette clé existe déjà.',
            'valeur.required' => 'La valeur est obligatoire.',
            'categorie.required' => 'La catégorie est obligatoire.',
            'categorie.in' => 'Catégorie non valide.',
            'type_donnee.required' => 'Le type de données est obligatoire.',
            'type_donnee.in' => 'Type de données non valide.'
        ];
    }
}