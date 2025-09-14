<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [
            'admin',
            'utilisateur',
            'moderateur',
            'conducteur_premium',
            'passager_regulier'
        ];

        $allPermissions = [
            'read', 'create', 'update', 'delete',
            'create_covoiturage', 'participate', 'moderate_content',
            'manage_users', 'manage_system', 'premium_features'
        ];

        // Sélectionner 2-5 permissions aléatoires
        $permissions = $this->faker->randomElements($allPermissions, $this->faker->numberBetween(2, 5));

        return [
            'nom_role' => $this->faker->unique()->randomElement($roles),
            'permissions' => $permissions, // Laravel va automatiquement encoder en JSON
        ];
    }

    /**
     * State for admin role
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'nom_role' => 'admin',
            'permissions' => ['all', 'create', 'read', 'update', 'delete', 'manage_users', 'manage_system'],
        ]);
    }

    /**
     * State for basic user role
     */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'nom_role' => 'utilisateur',
            'permissions' => ['read', 'create_covoiturage', 'participate'],
        ]);
    }

    /**
     * State for moderator role
     */
    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'nom_role' => 'moderateur',
            'permissions' => ['read', 'update', 'moderate_content', 'manage_covoiturages'],
        ]);
    }
}