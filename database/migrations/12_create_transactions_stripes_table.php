<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions_stripe', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->foreignId('utilisateur_id')->constrained('utilisateurs');
            $table->decimal('montant_euros', 8, 2);
            $table->integer('montant_credits');
            $table->string('status', 50);
            $table->timestamp('date_paiement')->useCurrent();
            $table->string('stripe_payment_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_stripes');
    }
};
