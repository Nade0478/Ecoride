<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'ecoride:create-admin
                            {email : Email de l\'administrateur}
                            {password : Mot de passe}
                            {--nom= : Nom de famille de l\'administrateur}
                            {--prenom= : Prénom de l\'administrateur}';

    protected $description = 'Créer un compte administrateur pour EcoRide';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $nom = $this->option('nom') ?? 'Admin';
        $prenom = $this->option('prenom') ?? 'EcoRide';

        // Vérifier si l'utilisateur existe déjà
        if (Utilisateur::where('email', $email)->exists()) {
            $this->error("Un utilisateur avec l'email {$email} existe déjà !");
            return 1;
        }

        try {
            $admin = Utilisateur::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => Hash::make($password),
                'id_role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->info("✅ Administrateur créé avec succès !");
            $this->line("Email : {$admin->email}");
            $this->line("Nom : {$admin->prenom} {$admin->nom}");
            $this->line("Rôle : {$admin->role}");

            return 0;

        } catch (\Exception $e) {
            $this->error("Erreur lors de la création de l'administrateur : " . $e->getMessage());
            return 1;
        }
    }
}
