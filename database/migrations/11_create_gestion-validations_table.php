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
            $table->foreignId('id_utilisateur')->constrained('utilisateurs')->onDelete('cascade');

            // Mouvement concerné
            $table->foreignId('id_mouvement_credit')->constrained('mouvement_credits')->onDelete('cascade');

            $table->enum('type_mouvement', ['credit', 'debit']);
            $table->decimal('montant', 8, 2);
            $table->dateTime('date_operation')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestion_validations');
    }
};
