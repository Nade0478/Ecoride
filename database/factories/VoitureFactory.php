<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voiture>
 */
class VoitureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modele' => $this->faker->word(),
            'annee' => $this->faker->year(),
            'couleur' => $this->faker->safeColorName(),
            'mileage' => $this->faker->numberBetween(0, 200000),
            'license_plate' => strtoupper($this->faker->bothify('??###??')),];
    }
}
