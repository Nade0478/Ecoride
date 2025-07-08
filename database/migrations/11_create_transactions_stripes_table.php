<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions_stripe', function (Blueprint $table) {
            $table->id('id_transaction');

            // Utilisateur lié à la transaction
            $table->foreignId('id_utilisateur')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');

            // Montants
            $table->unsignedDecimal('montant_euros', 8, 2)
                  ->comment('Montant payé via Stripe en euros');

            $table->unsignedInteger('montant_credits')
                  ->comment('Crédits attribués suite au paiement');

            // Info Stripe
            $table->string('stripe_payment_id', 100)->nullable()
                  ->comment('Identifiant unique de paiement Stripe');

            $table->string('status', 50)
                  ->default('en_attente')
                  ->comment('Statuts possibles : succeeded, failed, pending…');

            // Horodatage du paiement
            $table->dateTime('date_paiement')
                  ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_stripe');
    }
};
