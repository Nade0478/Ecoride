<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestion_validations', function (Blueprint $table) {
            $table->id('id_gestion_validation');

            // Utilisateur qui valide
            $table->foreignId('id_utilisateur')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');

            // Mouvement crédit concerné
            $table->foreignId('id_mouvement_credit')
                  ->constrained('mouvement_credits')
                  ->onDelete('cascade');

            // Type du mouvement validé : crédit ou débit
            $table->enum('type_mouvement', ['credit', 'debit'])
                  ->comment('Type du mouvement validé');

            // Montant validé (positif, typiquement)
            $table->unsignedDecimal('montant', 8, 2)
                  ->comment('Montant validé en crédits');

            // Date de l’opération validée
            $table->dateTime('date_operation')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestion_validations');
    }
};
