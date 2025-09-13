<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Covoiturage>
 */
class CovoiturageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'departure' => $this->faker->city(),
            'arrival' => $this->faker->city(),
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'seats_available' => $this->faker->numberBetween(1, 6),
            'price_per_seat' => $this->faker->randomFloat(2, 5, 100),
            'driver_name' => $this->faker->name(),
            'contact_info' => $this->faker->phoneNumber(),
            'vehicle_info' => $this->faker->company() . ' ' . $this->faker->word() . ' (' . $this->faker->year() . ')',
            'additional_info' => $this->faker->optional()->sentence(),
        ];
    }
}
