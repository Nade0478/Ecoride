<?php

namespace Database\Factories;

use App\Models\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigurationFactory extends Factory
{
    protected $model = Configuration::class;

    public function definition(): array
    {
        return [
            'cle' => fake()->unique()->slug(2, '_'),
            'categorie' => fake()->randomElement(['general', 'covoiturage', 'paiement', 'notifications']),
            'valeur' => fake()->randomElement([
                fake()->randomFloat(2, 0.50, 2.00),
                fake()->numberBetween(1, 6),
                fake()->boolean(),
                fake()->sentence(3)
            ]),
            'type_donnee' => fake()->randomElement(['float', 'integer', 'boolean', 'string', 'json']),
            'description' => fake()->sentence(6, 12),
            'modifiable_interface' => fake()->boolean(70),
            'cache_requis' => fake()->boolean(30),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function covoiturage(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'covoiturage',
            'cle' => fake()->randomElement([
                'prix_par_km', 'max_passagers', 'delai_annulation_gratuit',
                'distance_max_detour', 'temps_attente_max', 'commission_plateforme'
            ]),
            'type_donnee' => fake()->randomElement(['float', 'integer']),
            'valeur' => match(fake()->randomElement(['prix', 'nombre', 'temps'])) {
                'prix' => fake()->randomFloat(2, 0.25, 1.50),
                'nombre' => fake()->numberBetween(1, 4),
                'temps' => fake()->numberBetween(2, 15),
            },
        ]);
    }

    public function notifications(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'notifications',
            'cle' => fake()->randomElement([
                'push_enabled', 'email_enabled', 'sms_enabled', 'quiet_hours_start'
            ]),
            'type_donnee' => fake()->randomElement(['boolean', 'string']),
            'valeur' => fake()->randomElement([fake()->boolean(80), fake()->time('H:i')]),
        ]);
    }

    public function ecologique(): static
    {
        return $this->state(fn (array $attributes) => [
            'categorie' => 'ecologique',
            'cle' => fake()->randomElement([
                'bonus_co2_par_km', 'credits_covoiturage', 'seuil_bonus_ecologique'
            ]),
            'type_donnee' => fake()->randomElement(['float', 'integer', 'boolean']),
            'valeur' => match(fake()->randomElement(['bonus', 'calculation', 'display'])) {
                'bonus' => fake()->randomFloat(3, 0.001, 0.050),
                'calculation' => fake()->randomFloat(1, 120, 250),
                'display' => fake()->boolean(90),
            },
        ]);
    }
}