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
        Schema::create('participations', function (Blueprint $table) {
            $table->unsignedBigInteger('id_covoiturage');
            $table->unsignedBigInteger('id_user');
            $table->dateTime('date_inscription');
            $table->dateTime('date_validation')->nullable();
            $table->string('statut');
            $table->boolean('presente')->default(false);
            $table->timestamps();

            // Clé primaire composite
            $table->primary(['id_covoiturage', 'id_user']);

            // Clés étrangères
            $table->foreign('id_covoiturage')->references('id_covoiturage')->on('covoiturages')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};