<?php

namespace Database\Seeders;

use App\Models\Intervenant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Intervenant::factory()->create([
            'name' => 'Test Intervenant',
            'email' => 'test@example.com',
        ]);
    }
}
