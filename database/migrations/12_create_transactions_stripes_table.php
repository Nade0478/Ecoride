<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('transactions_stripe', function (Blueprint $table) {
            $table->id('id_transaction');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->decimal('montant_euros', 8, 2); // Montant payé en € via Stripe
            $table->integer('montant_credits');     // Nombre de crédits obtenus
            $table->string('stripe_payment_id', 100)->nullable(); // ID renvoyé par Stripe
            $table->string('status', 50)->default('en_attente');  // succeeded, failed, pending...
            $table->timestamp('date_transaction')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_stripe');
    }
};
