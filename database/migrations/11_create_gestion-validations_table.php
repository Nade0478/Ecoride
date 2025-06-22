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
        Schema::create('gestion_validations', function (Blueprint $table) {
            $table->id('id_gestion_validation');

            // Relation avec l'user qui valide
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            // Relation avec le mouvement de crédit concerné
            $table->unsignedBigInteger('id_mouvement_credit');
            $table->foreign('id_mouvement_credit')->references('id_mouvement_credit')->on('mouvement_credits')->onDelete('cascade');

            $table->enum('type_mouvement', ['credit', 'debit']);
            $table->timestamp('date_operation')->useCurrent();
            $table->decimal('montant', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_validations');
    }
};
