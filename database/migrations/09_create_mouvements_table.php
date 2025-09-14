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
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id('id_mouvement');
            $table->string('type_mouvement');
            $table->decimal('montant', 8, 2);
            $table->dateTime('date_operation');
            $table->text('description')->nullable();
            $table->string('id_regle_credit')->nullable();
            $table->string('id_covoiturage')->nullable();
            $table->unsignedBigInteger('id_utilisateur');
            $table->timestamps();

            // Clé étrangère vers utilisateurs
            $table->foreign('id_utilisateur')->references('id')->on('utilisateurs')->onDelete('cascade');

            // Index pour améliorer les performances
            $table->index(['id_utilisateur', 'date_operation']);
            $table->index('type_mouvement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};