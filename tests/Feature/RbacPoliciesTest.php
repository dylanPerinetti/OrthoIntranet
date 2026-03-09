<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacPoliciesTest extends TestCase
{
    use RefreshDatabase;

    public function test_assistante_cannot_create_user_story(): void
    {
        $assistante = User::factory()->create(['role' => 'assistante']);
        [$project, $stream] = $this->seedProjectAndStream();

        $payload = [
            'title' => 'Story non autorisée',
            'description' => 'test',
            'stream_id' => $stream->id,
            'status' => 'todo',
            'priority' => 'medium',
        ];

        $this->actingAs($assistante)
            ->post('/user-stories', $payload)
            ->assertForbidden();
    }

    public function test_secretaire_can_create_user_story(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire']);
        [$project, $stream] = $this->seedProjectAndStream();

        $this->actingAs($secretaire)
            ->post('/user-stories', [
                'title' => 'Story autorisée',
                'description' => 'test',
                'stream_id' => $stream->id,
                'status' => 'todo',
                'priority' => 'high',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('user_stories', [
            'title' => 'Story autorisée',
            'created_by' => $secretaire->id,
        ]);
    }

    public function test_only_admin_can_create_sprint(): void
    {
        $project = Project::create(['name' => 'Ortho Projet']);
        $secretaire = User::factory()->create(['role' => 'secretaire']);
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'name' => 'Sprint test',
            'number' => 1,
            'goal' => 'Objectif',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'status' => 'planned',
        ];

        $this->actingAs($secretaire)
            ->post('/sprints', $payload)
            ->assertForbidden();

        $this->actingAs($admin)
            ->post('/sprints', $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('sprints', [
            'name' => 'Sprint test',
            'project_id' => $project->id,
        ]);
    }

    private function seedProjectAndStream(): array
    {
        $project = Project::create(['name' => 'Ortho Projet']);
        $stream = Stream::create([
            'project_id' => $project->id,
            'name' => 'Opérationnel',
            'slug' => 'operationnel',
            'description' => 'desc',
            'color' => '#3b82f6',
            'icon' => 'Settings',
            'sort_order' => 1,
        ]);

        return [$project, $stream];
    }
}
