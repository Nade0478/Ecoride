<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id('id_carModel');
            $table->string('nmodele', 50);
            $table->integer('nb_places');
            $table->string('type', 10);
            $table->foreignId('id_marque')
                  ->constrained('marques')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carModels');
    }
};
