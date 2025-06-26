<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions_stripe', function (Blueprint $table) {
            $table->id('id_transaction');

            $table->foreignId('id_utilisateur')->constrained('utilisateurs')->onDelete('cascade');

            $table->decimal('montant_euros', 8, 2); // Montant payé en € via Stripe
            $table->integer('montant_credits');     // Crédits attribués
            $table->string('stripe_payment_id', 100)->nullable(); // ID Stripe
            $table->string('status', 50)->default('en_attente');  // Statuts possibles : succeeded, failed, pending...
            $table->dateTime('date_paiement')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_stripe');
    }
};
