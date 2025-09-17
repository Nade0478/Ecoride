<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method \App\Models\User|null user()
 * @method mixed route(string $key = null)
 */
class UpdateConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $configuration = $this->route('configuration');

        // Admin peut tout modifier
        if ($user && $user->hasRole('admin')) {
            return true;
        }

        // Employé peut modifier certaines catégories
        if ($user && $user->hasRole('employee')) {
            $categoriesAutorisees = ['general', 'moderation', 'notifications'];
            return in_array($configuration->categorie, $categoriesAutorisees);
        }

        return false;
    }

    public function rules(): array
    {
        $configuration = $this->route('configuration');

        return [
            'cle' => [
                'sometimes', 'string', 'max:100',
                Rule::unique('configurations', 'cle')->ignore($configuration->id_conf, 'id_conf')
            ],
            'valeur' => 'sometimes|required',
            'categorie' => [
                'sometimes', 'string',
                Rule::in(['general', 'covoiturage', 'moderation', 'dashboard', 'securite', 'notifications', 'paiement'])
            ],
            'description' => 'sometimes|nullable|string|max:500',
            'type_donnee' => [
                'sometimes', 'string',
                Rule::in(['string', 'integer', 'float', 'boolean', 'json'])
            ],
            'modifiable_interface' => 'sometimes|boolean',
            'cache_requis' => 'sometimes|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'cle.unique' => 'Cette clé existe déjà.',
            'valeur.required' => 'La valeur est obligatoire.',
            'categorie.in' => 'Catégorie non valide.',
            'type_donnee.in' => 'Type de données non valide.'
        ];
    }
}