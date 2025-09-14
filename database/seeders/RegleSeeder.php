<?php

namespace Database\Seeders;

use App\Models\Regle;
use Illuminate\Database\Seeder;

class RegleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regles = [
            [
                'actif' => true,
                'montant_credit' => 10.00,
                'type_action' => 'inscription',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'actif' => true,
                'montant_credit' => 5.00,
                'type_action' => 'covoiturage_conducteur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'actif' => true,
                'montant_credit' => 2.00,
                'type_action' => 'covoiturage_passager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'actif' => true,
                'montant_credit' => 15.00,
                'type_action' => 'parrainage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'actif' => true,
                'montant_credit' => 1.00,
                'type_action' => 'avis_positif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'actif' => false,
                'montant_credit' => 20.00,
                'type_action' => 'bonus_mensuel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($regles as $regle) {
            Regle::create($regle);
        }
    }
}