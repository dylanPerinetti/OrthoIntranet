<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_story_writes_audit_log(): void
    {
        $user = User::factory()->create(['role' => 'secretaire']);
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

        $this->actingAs($user)
            ->post('/user-stories', [
                'title' => 'Story audit',
                'description' => 'test',
                'stream_id' => $stream->id,
                'status' => 'todo',
                'priority' => 'medium',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'user_story.created',
        ]);
    }
}
