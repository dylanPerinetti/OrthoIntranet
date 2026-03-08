<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Stream;
use Illuminate\Database\Seeder;

class StreamSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::firstOrCreate(
            ['name' => 'OrthoIntranet'],
            [
                'description' => 'Plateforme de pilotage IA pour le cabinet d\'orthodontie du Dr André',
                'status' => 'active',
            ]
        );

        $streams = [
            [
                'name'        => 'Opérationnel',
                'slug'        => 'operationnel',
                'description' => 'Automatisation des tâches quotidiennes du cabinet pour libérer du temps au personnel soignant.',
                'color'       => '#10b981',
                'icon'        => 'Settings',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Marketing',
                'slug'        => 'marketing',
                'description' => 'Stratégie de fidélisation interne et acquisition externe automatisée.',
                'color'       => '#8b5cf6',
                'icon'        => 'Megaphone',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Expertise Dr Z',
                'slug'        => 'expertise-dr-z',
                'description' => 'Capitalisation sur 800 pages de cours d\'orthodontie pour créer un assistant IA.',
                'color'       => '#3b82f6',
                'icon'        => 'GraduationCap',
                'sort_order'  => 3,
            ],
        ];

        foreach ($streams as $stream) {
            Stream::firstOrCreate(
                ['slug' => $stream['slug']],
                array_merge($stream, ['project_id' => $project->id])
            );
        }
    }
}
