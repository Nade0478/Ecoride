<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'manufacturer' => $this->faker->company(),
            'year' => $this->faker->year(),
            'type' => $this->faker->randomElement(['Sedan', 'SUV', 'Truck', 'Coupe', 'Convertible']),
            'price' => $this->faker->numberBetween(20000, 100000),
            'in_stock' => $this->faker->boolean(80), // 80% chance of being in stock
        ];
    }
}
