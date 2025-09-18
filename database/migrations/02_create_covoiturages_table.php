<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('covoiturages', function (Blueprint $table) {
            $table->id('id_covoiturage');
            $table->date('date_depart');
            $table->date('date_arrivee')->nullable();
            $table->time('heure_depart');
            $table->time('heure_arrivee')->nullable();
            $table->string('lieu_depart', 100);
            $table->string('lieu_arrivee', 100);
            $table->unsignedInteger('nombre_place');
            $table->boolean('ecologique')->default(false);
            $table->boolean('accepte_fumeur')->default(false);
            $table->boolean('accepte_animal')->default(false);
            $table->unsignedBigInteger('prix_credit');
            $table->string('statut', 50)->default('disponible');

            // Relations
            $table->foreignId('id_user')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('id_voiture')
                  ->constrained('voitures')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('covoiturages');
    }
};
