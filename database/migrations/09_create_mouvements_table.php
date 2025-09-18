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
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            // Clé étrangère vers users
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Index pour améliorer les performances
            $table->index(['id_user', 'date_operation']);
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