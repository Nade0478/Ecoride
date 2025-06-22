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
        Schema::create('mouvement_credits', function (Blueprint $table) {
            $table->id('id_mouvement_credit');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->enum('type_mouvement', ['credit', 'debit']);
            $table->decimal('montant', 8, 2);
            $table->string('motif', 100)->nullable(); // optionnel : bonus, achat, remboursement, etc.
            $table->timestamp('date_mouvement')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_credits');
    }
};
