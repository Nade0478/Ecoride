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
        Schema::create('regles', function (Blueprint $table) {
            $table->id('id_regle');
            $table->boolean('actif')->default(true);
            $table->decimal('montant_credit', 8, 2);
            $table->string('type_action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regles');
    }
};