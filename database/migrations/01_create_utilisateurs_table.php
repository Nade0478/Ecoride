<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table utilisateurs
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('telephone', 50)->nullable();
            $table->string('adresse', 50)->nullable();
            $table->string('cp', 10)->nullable();
            $table->string('ville', 50)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('photo', 100)->nullable();
            $table->string('pseudo', 50)->nullable();
            $table->integer('credit_depenser')->default(0);
            $table->integer('credit_gagner')->default(0);

            $table->foreignId('id_role')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
        });

        // Table de réinitialisation de mot de passe
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Table sessions Laravel standard
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            // Correction ici : Laravel attend user_id, pas id_utilisateur
            $table->foreignId('id_user')->nullable()->constrained('utilisateurs')->onDelete('cascade');

            $table->string('ip_address', 45)->nullable();

            // Correction : Laravel utilise user_agent
            $table->text('user_agent')->nullable();

            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('utilisateurs');
    }
};
