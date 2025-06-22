<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('covoiturages', function (Blueprint $table) {
            $table->id('id_covoiturage');
            $table->timestamp('date_depart');
            $table->timestamp('date_arrivee')->nullable();
            $table->string('lieu_depart', 100);
            $table->string('lieu_arrivee', 100);
            $table->integer('places_disponibles')->default(0);

            // Relations
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('id_voiture');
            $table->foreign('id_voiture')->references('id_voiture')->on('voitures')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covoiturages');
    }
};
