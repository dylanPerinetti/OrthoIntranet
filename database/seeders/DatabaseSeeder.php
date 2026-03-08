<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Compte Admin — Dr André
        User::firstOrCreate(
            ['email' => 'andre@orthodz.fr'],
            [
                'name'     => 'Dr André',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // Compte Secrétaire
        User::firstOrCreate(
            ['email' => 'secretaire@orthodz.fr'],
            [
                'name'     => 'Marie Dupont',
                'password' => bcrypt('password'),
                'role'     => 'secretaire',
            ]
        );

        // Compte Assistante
        User::firstOrCreate(
            ['email' => 'assistante@orthodz.fr'],
            [
                'name'     => 'Sophie Martin',
                'password' => bcrypt('password'),
                'role'     => 'assistante',
            ]
        );

        $this->call([
            StreamSeeder::class,
            UserStorySeeder::class,
            PersonaSeeder::class,
        ]);
    }
}
