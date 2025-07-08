<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id('id_configuration');
            $table->string('cle', 100)->unique();         // ex : 'credit_initial'
            $table->text('valeur')->nullable();           // ex : '100', 'true', 'tarif=0.25'
            $table->string('categorie', 50)->nullable();  // ex : 'tarifs', 'app', 'systeme'
            $table->string('description', 255)->nullable(); // pour aider les admins
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
