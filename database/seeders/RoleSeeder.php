<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access',
        ]);

        \App\Models\Role::create([
            'name' => 'user',
            'description' => 'Regular user with limited access',
        ]);

        \App\Models\Role::create([
            'name' => 'guest',
            'description' => 'Guest user with minimal access',
        ]);
        \App\Models\Role::create([
            'name' => 'moderator',
            'description' => 'User with permissions to moderate content',
        ]);
        \App\Models\Role::create([
            'name' => 'editor',
            'description' => 'User with permissions to edit content',
        ]);
    }
}
