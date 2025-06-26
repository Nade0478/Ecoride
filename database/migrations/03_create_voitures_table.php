<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voitures', function (Blueprint $table) {
            $table->id('id_voiture');
            $table->string('immatriculation', 50);
            $table->string('couleur', 50);
            $table->string('energie', 50);
            $table->date('date_mise_en_circulation');

            // Relations
            $table->foreignId('id_model')->constrained('car_models');
            $table->foreignId('id_utilisateur')->constrained('utilisateurs');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voitures');
    }
};
