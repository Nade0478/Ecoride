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
        Schema::create('voitures', function (Blueprint $table) {
            $table->id('id_voiture');
            $table->string('immatriculation', 50);
            $table->string('couleur', 50);
            $table->string('energie', 50);
            $table->date('date_mise_en_circulation');
            $table->foreignId('car_model_id')->constrained('car_models');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voitures');
    }
};
