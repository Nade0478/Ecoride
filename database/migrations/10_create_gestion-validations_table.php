<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestion_validations', function (Blueprint $table) {
            $table->id();

            // Lien vers l'utilisateur qui valide
            $table->foreignId('user_id')->nullable()->constrained('utilisateurs')->onDelete('set null');

            // Type de validation (ex : avis, crédit, trajet)
            $table->string('type_validation', 50);

            // Statut (validé, refusé, en attente)
            $table->enum('statut', ['valide', 'refuse', 'en_attente'])->default('en_attente');

            // Montant éventuellement validé (si lié à un crédit)
            $table->decimal('montant_valide', 8, 2)->unsigned()->nullable(); // ✅ correction ici

            // Commentaire ou justification
            $table->text('commentaire')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestion_validations');
    }
};
