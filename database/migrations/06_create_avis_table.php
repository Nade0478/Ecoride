<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id('id_avis');

            // Relations
            $table->foreignId('id_utilisateur')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');

            $table->foreignId('id_covoiturage')
                  ->constrained('covoiturages')
                  ->onDelete('cascade');

            // Données de l'avis
            $table->unsignedTinyInteger('note')
                  ->comment('Note de 1 à 5'); // unsigned évite les valeurs négatives

            $table->string('statut', 20)
                  ->default('en_attente')
                  ->comment("Statut de l'avis : en_attente, approuve, refuse");

            $table->text('commentaire')
                  ->nullable()
                  ->comment('Commentaire facultatif sur le covoiturage ou le conducteur');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
