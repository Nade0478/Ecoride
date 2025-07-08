<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvement_credits', function (Blueprint $table) {
            $table->id('id_mouvement_credit');

            $table->foreignId('id_utilisateur')->constrained('utilisateurs')->onDelete('cascade');

            $table->enum('type_mouvement', ['credit', 'debit']);
            $table->decimal('montant', 8, 2);
            $table->string('motif', 100)->nullable(); // Ex : bonus, achat, remboursement...
            $table->dateTime('date_operation')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvement_credits');
    }
};
