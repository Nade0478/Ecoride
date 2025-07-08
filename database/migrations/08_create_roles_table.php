<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_role');
            $table->string('nom_role', 50)->unique(); // ex : "Administrateur", "Salarié", "Conducteur", "Passager"
            $table->json('permissions')->nullable(); // Format JSON plus flexible pour stocker plusieurs droits
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
