<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GestionValidation>
 */
class GestionValidationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'comments' => $this->faker->optional()->sentence(),
            'validated_by' => \App\Models\User::factory(), // Assuming a relation to User model
            'validated_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }
}
