<?php

namespace Database\Factories;

use App\Models\Participation;
use App\Models\Utilisateur;
use App\Models\Covoiturage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participation>
 */
class ParticipationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Participation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateInscription = $this->faker->dateTimeBetween('-2 months', 'now');
        $statut = $this->faker->randomElement(['en_attente', 'confirmee', 'annulee']);

        return [
            'id_covoiturage' => Covoiturage::factory(),
            'id_utilisateur' => Utilisateur::factory(),
            'date_inscription' => $dateInscription,
            'date_validation' => $statut === 'confirmee'
                ? $this->faker->dateTimeBetween($dateInscription, 'now')
                : null,
            'statut' => $statut,
            'presente' => $statut === 'confirmee'
                ? $this->faker->boolean(80) // 80% de chance d'être présent si confirmé
                : false,
        ];
    }

    /**
     * State for pending participation
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_attente',
            'date_validation' => null,
            'presente' => false,
        ]);
    }

    /**
     * State for confirmed participation
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'confirmee',
            'date_validation' => $this->faker->dateTimeBetween($attributes['date_inscription'] ?? '-1 week', 'now'),
            'presente' => $this->faker->boolean(85),
        ]);
    }

    /**
     * State for cancelled participation
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'annulee',
            'date_validation' => null,
            'presente' => false,
        ]);
    }

    /**
     * State for present participant
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'confirmee',
            'presente' => true,
            'date_validation' => $this->faker->dateTimeBetween($attributes['date_inscription'] ?? '-1 week', 'now'),
        ]);
    }

    /**
     * State for specific user and covoiturage
     */
    public function forUserAndCovoiturage(int $userId, int $covoiturageId): static
    {
        return $this->state(fn (array $attributes) => [
            'id_utilisateur' => $userId,
            'id_covoiturage' => $covoiturageId,
        ]);
    }
}