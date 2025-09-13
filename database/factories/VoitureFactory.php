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
            'model' => $this->faker->word(),
            'year' => $this->faker->year(),
            'color' => $this->faker->safeColorName(),
            'mileage' => $this->faker->numberBetween(0, 200000),
            'price' => $this->faker->numberBetween(5000, 100000),
            'in_stock' => $this->faker->boolean(70), // 70% chance of being in stock
        ];
    }
}
