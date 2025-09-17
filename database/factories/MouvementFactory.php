<?php

namespace Database\Factories;

use App\Models\Mouvement;
use App\Models\Utilisateur;
use App\Models\Regle;
use App\Models\Covoiturage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mouvement>
 */
class MouvementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Mouvement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeMouvement = $this->faker->randomElement(['credit', 'debit']);

        $descriptionsCredit = [
            'Bonus d\'inscription',
            'Crédit covoiturage conducteur',
            'Crédit covoiturage passager',
            'Bonus parrainage',
            'Crédit avis positif',
            'Bonus mensuel',
            'Récompense participation événement'
        ];

        $descriptionsDebit = [
            'Achat produit éco',
            'Participation événement',
            'Réservation véhicule',
            'Service premium',
            'Frais annulation',
            'Achat accessoire écologique'
        ];

        return [
            'type_mouvement' => $typeMouvement,
            'montant' => $typeMouvement === 'credit'
                ? $this->faker->randomFloat(2, 1.00, 20.00)
                : $this->faker->randomFloat(2, 0.50, 15.00),
            'date_operation' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'description' => $typeMouvement === 'credit'
                ? $this->faker->randomElement($descriptionsCredit)
                : $this->faker->randomElement($descriptionsDebit),
            'id_regle_credit' => $typeMouvement === 'credit' && $this->faker->boolean(70)
                ? Regle::factory()
                : null,
            'id_covoiturage' => $this->faker->boolean(40)
                ? Covoiturage::factory()
                : null,
            'id_utilisateur' => Utilisateur::factory(),
        ];
    }

    /**
     * State for credit movement
     */
    public function credit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_mouvement' => 'credit',
            'montant' => $this->faker->randomFloat(2, 1.00, 25.00),
            'description' => $this->faker->randomElement([
                'Bonus d\'inscription',
                'Crédit covoiturage conducteur',
                'Crédit covoiturage passager',
                'Bonus parrainage',
                'Crédit avis positif'
            ]),
            'id_regle_credit' => Regle::factory(),
        ]);
    }

    /**
     * State for debit movement
     */
    public function debit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_mouvement' => 'debit',
            'montant' => $this->faker->randomFloat(2, 0.50, 15.00),
            'description' => $this->faker->randomElement([
                'Achat produit éco',
                'Participation événement',
                'Réservation véhicule',
                'Service premium'
            ]),
            'id_regle_credit' => null,
        ]);
    }

    /**
     * State for high amount
     */
    public function highAmount(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant' => $this->faker->randomFloat(2, 20.00, 50.00),
        ]);
    }

    /**
     * State for low amount
     */
    public function lowAmount(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant' => $this->faker->randomFloat(2, 0.10, 2.00),
        ]);
    }

    /**
     * State for specific user
     */
    public function forUser(int $Iduser): static
    {
        return $this->state(fn (array $attributes) => [
            'id_utilisateur' => $Iduser,
        ]);
    }

    /**
     * State for covoiturage related movement
     */
    public function covoiturageRelated(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_mouvement' => 'credit',
            'description' => $this->faker->randomElement([
                'Crédit covoiturage conducteur',
                'Crédit covoiturage passager'
            ]),
            'id_covoiturage' => Covoiturage::factory(),
            'id_regle_credit' => Regle::factory(),
        ]);
    }

    /**
     * State for inscription bonus
     */
    public function inscriptionBonus(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_mouvement' => 'credit',
            'montant' => 10.00,
            'description' => 'Bonus d\'inscription - Bienvenue !',
            'id_regle_credit' => Regle::factory()->forAction('inscription'),
        ]);
    }
}