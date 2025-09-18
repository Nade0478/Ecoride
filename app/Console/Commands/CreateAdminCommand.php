<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User; 
use Illuminate\Console\Command;
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

        if (User::where('email', $email)->exists()) {
            $this->error("❌ Un user avec l'email {$email} existe déjà !");
            return Command::FAILURE;
        }

        $role = Role::where('nom', 'admin')->first();

        if (!$role) {
            $this->error("❌ Le rôle 'admin' n'existe pas dans la table roles.");
            return Command::FAILURE;
        }

        try {
            $user = User::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => Hash::make($motDePasse),
                'id_role' => $role->id,
            ]);

            $this->info("✅ Administrateur créé avec succès : {$user->email}");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la création de l'administrateur : " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
