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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('covoiturage_id')->constrained('covoiturages')->onDelete('cascade');
            $table->integer('note')->comment('Note de 1 à 5');
            $table->string('statut', 20)->default('en_attente')->comment('Statut de l\'avis : en_attente, approuve, refuse');
            $table->text('commentaire')->nullable()->comment('Commentaire facultatif sur la voiture');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
