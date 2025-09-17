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
                            {motdepasse : Mot de passe}
                            {--nom= : Nom de l\'administrateur}
                            {--prenom= : Prénom de l\'administrateur}';

    protected $description = 'Créer un administrateur pour EcoRide';

    public function handle()
    {
        $email = $this->argument('email');
        $motDePasse = $this->argument('motdepasse');
        $nom = $this->option('nom') ?? 'Admin';
        $prenom = $this->option('prenom') ?? 'EcoRide';

        // 🔍 Vérifier si l'utilisateur existe déjà
        if (Utilisateur::where('email', $email)->exists()) {
            $this->error("❌ Un utilisateur avec l'email {$email} existe déjà !");
            return Command::FAILURE;
        }

        // 🔍 Récupérer l'ID du rôle 'admin'
        $role = Role::where('nom', 'admin')->first();

        if (!$role) {
            $this->error("❌ Le rôle 'admin' n'existe pas dans la table roles.");
            return Command::FAILURE;
        }

        try {
            $utilisateur = Utilisateur::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => Hash::make($motDePasse),
                'id_role' => $role->id, // Utiliser l'ID du rôle
            ]);

            $this->info("✅ Administrateur créé avec succès : {$utilisateur->email}");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la création de l'administrateur : " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
