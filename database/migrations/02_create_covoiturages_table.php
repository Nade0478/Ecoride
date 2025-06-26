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
            $table->string('lieu_depart', 50);
            $table->string('lieu_arrivee', 50);
            $table->integer('nombre_place');
            $table->boolean('ecologique');
            $table->boolean('accepte_fumeur');
            $table->boolean('accepte_animal');

            // Relations
            $table->unsignedBigInteger('id_utilisateur');
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');

            $table->unsignedBigInteger('id_voiture');
            $table->foreign('id_voiture')->references('id_voiture')->on('voitures')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('covoiturages');
    }
};