<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvement_credits', function (Blueprint $table) {
            $table->id();

            // Lien vers l'utilisateur concerné
            $table->foreignId('user_id')->constrained('utilisateurs')->onDelete('cascade');

            // Lien vers la validation éventuelle (peut être null)
            $table->foreignId('gestion_validation_id')->nullable()->constrained('gestion_validations')->onDelete('set null');

            // Type de mouvement : ajout, retrait, paiement, etc.
            $table->enum('type_mouvement', ['ajout', 'retrait', 'paiement']);

            // Date de l'opération
            $table->dateTime('date_operation');

            // Montant du mouvement (positif, décimal)
            $table->decimal('montant', 8, 2)->unsigned(); // ✅ correction ici

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvement_credits');
    }
};
