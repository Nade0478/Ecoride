<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marque>
 */
class MarqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nmarque' => $this->faker->company(),
            'nb_places' => $this->faker->numberBetween(2, 8),
            'type' => $this->faker->randomElement(['sedan', 'suv', 'hatchback', 'convertible', 'coupe']),
            'id_carModel' => \App\Models\CarModel::factory(),
        ];
    }
}
