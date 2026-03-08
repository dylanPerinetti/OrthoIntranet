<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\UserStory;
use Illuminate\Database\Seeder;

class SprintSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::first();

        $sprints = [
            [
                'name'       => 'Sprint 1 — Fondations',
                'number'     => 1,
                'goal'       => 'Mettre en place l\'infrastructure technique, l\'authentification et le backlog projet sur l\'intranet.',
                'start_date' => '2026-03-01',
                'end_date'   => '2026-03-14',
                'status'     => 'active',
            ],
            [
                'name'       => 'Sprint 2 — Secrétariat IA',
                'number'     => 2,
                'goal'       => 'Automatiser les rappels patients, confirmations RDV et gestion du planning via agent IA.',
                'start_date' => '2026-03-15',
                'end_date'   => '2026-03-28',
                'status'     => 'planned',
            ],
            [
                'name'       => 'Sprint 3 — Marketing & Fidélisation',
                'number'     => 3,
                'goal'       => 'Déployer les campagnes de fidélisation automatiques et le suivi des canaux d\'acquisition.',
                'start_date' => '2026-03-29',
                'end_date'   => '2026-04-11',
                'status'     => 'planned',
            ],
            [
                'name'       => 'Sprint 4 — Expertise Dr Z',
                'number'     => 4,
                'goal'       => 'Intégrer la base de connaissances orthodontiques et l\'assistant IA d\'aide au diagnostic.',
                'start_date' => '2026-04-12',
                'end_date'   => '2026-04-25',
                'status'     => 'planned',
            ],
        ];

        foreach ($sprints as $data) {
            Sprint::firstOrCreate(
                ['number' => $data['number']],
                array_merge($data, ['project_id' => $project->id])
            );
        }

        // Assign existing user stories to Sprint 1
        $sprint1 = Sprint::where('number', 1)->first();
        if ($sprint1) {
            UserStory::whereNull('sprint_id')->update(['sprint_id' => $sprint1->id]);
        }
    }
}
