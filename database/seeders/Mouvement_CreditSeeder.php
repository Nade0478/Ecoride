<?php

namespace Database\Seeders;

use App\Models\MouvementCredit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Mouvement_CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MouvementCredit::factory(10)->create();
    }
}
