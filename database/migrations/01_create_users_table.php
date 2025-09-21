<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telephone', 50)->nullable();
            $table->string('adresse', 50)->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('ville', 50)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('photo', 100)->nullable();
            $table->string('pseudo', 50)->nullable();
            $table->integer('credits')->default(0);
            $table->string('statut', 50)->default('actif');
            $table->rememberToken();
            $table->foreignId('id_role')->constrained('roles', 'id_role')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};