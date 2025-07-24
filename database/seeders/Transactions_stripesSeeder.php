<?php

namespace Database\Seeders;

use App\Models\TransactionStripe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Transactions_stripesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionStripe::factory(20)->create();
    }
}
