<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvement_credits', function (Blueprint $table) {
            $table->id('id_mouvement_credit');

            // Relation avec les utilisateurs
            $table->foreignId('id_utilisateur')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');

            // Type du mouvement : credit ou debit
            $table->enum('type_mouvement', ['credit', 'debit'])
                  ->default('credit')
                  ->comment('Type de mouvement financier');

            // Montant en crédits, positif
            $table->unsignedDecimal('montant', 8, 2)
                  ->comment('Montant du mouvement en crédits');

            // Motif métier : achat, bonus, remboursement, etc.
            $table->string('motif', 100)->nullable()
                  ->comment('Ex : bonus, achat, remboursement');

            // Date de l’opération (ajout automatique par défaut)
            $table->dateTime('date_operation')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvement_credits');
    }
};
