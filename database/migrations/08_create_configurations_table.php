<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id('id_parametre');
            $table->string('propriete', 50)->unique();
            $table->string('valeur', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};
