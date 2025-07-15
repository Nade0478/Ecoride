<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions_stripes', function (Blueprint $table) {
            $table->id();

            // Lien vers l'utilisateur qui a effectué la transaction
            $table->foreignId('user_id')->constrained('utilisateurs')->onDelete('cascade');

            // Identifiant Stripe
            $table->string('stripe_transaction_id')->unique();

            // Montant de la transaction
            $table->decimal('montant', 8, 2)->unsigned();

            // Statut de la transaction
            $table->enum('statut', ['en_attente', 'valide', 'refuse'])->default('en_attente');

            // Date de la transaction
            $table->dateTime('date_transaction');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_stripes');
    }
};
