<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametresApps', function (Blueprint $table) {
            $table->id('id_parametresApp');
            $table->string('propriete', 50)->unique();
            $table->string('valeur', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametresApps');
    }
};
