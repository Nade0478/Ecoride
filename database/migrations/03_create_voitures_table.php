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
            $table->string('immatriculation', 20)->unique(); // plus réaliste et unique
            $table->string('couleur', 30);
            $table->string('energie', 30);
            $table->date('date_mise_en_circulation');

            // Relations
            $table->foreignId('id_model')
                  ->constrained('car_models')
                  ->onDelete('cascade');

            $table->foreignId('id_user')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voitures');
    }
};
