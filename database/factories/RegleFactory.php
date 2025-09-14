<?php

namespace Database\Factories;

use App\Models\Regle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Regle>
 */
class RegleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Regle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typesActions = [
            'inscription',
            'covoiturage_conducteur',
            'covoiturage_passager',
            'parrainage',
            'avis_positif',
            'bonus_mensuel',
            'participation_evenement',
            'completion_profil'
        ];

        return [
            'actif' => $this->faker->boolean(80), // 80% de chance d'être actif
            'montant_credit' => $this->faker->randomFloat(2, 0.50, 25.00),
            'type_action' => $this->faker->randomElement($typesActions),
        ];
    }

    /**
     * State for active rules
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => true,
        ]);
    }

    /**
     * State for inactive rules
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }

    /**
     * State for high credit amount
     */
    public function highCredit(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant_credit' => $this->faker->randomFloat(2, 15.00, 50.00),
        ]);
    }

    /**
     * State for specific action type
     */
    public function forAction(string $action): static
    {
        return $this->state(fn (array $attributes) => [
            'type_action' => $action,
        ]);
    }
}