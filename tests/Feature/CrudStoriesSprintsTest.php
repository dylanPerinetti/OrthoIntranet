<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Stream;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudStoriesSprintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_crud_sprint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Project::create(['name' => 'Ortho Projet']);

        $this->actingAs($admin)
            ->post('/sprints', [
                'name' => 'Sprint CRUD',
                'number' => 5,
                'goal' => 'Objectif initial',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
                'status' => 'planned',
            ])
            ->assertRedirect();

        $sprint = Sprint::where('number', 5)->firstOrFail();

        $this->actingAs($admin)
            ->put('/sprints/' . $sprint->id, [
                'name' => 'Sprint CRUD MAJ',
                'number' => 5,
                'goal' => 'Objectif modifié',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
                'status' => 'active',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('sprints', [
            'id' => $sprint->id,
            'name' => 'Sprint CRUD MAJ',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->delete('/sprints/' . $sprint->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('sprints', ['id' => $sprint->id]);
    }

    public function test_secretaire_can_crud_story_except_delete(): void
    {
        $secretaire = User::factory()->create(['role' => 'secretaire']);
        $admin = User::factory()->create(['role' => 'admin']);

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

        $this->actingAs($secretaire)
            ->post('/user-stories', [
                'title' => 'Story CRUD',
                'description' => 'init',
                'stream_id' => $stream->id,
                'status' => 'todo',
                'priority' => 'medium',
            ])
            ->assertRedirect();

        $story = UserStory::where('title', 'Story CRUD')->firstOrFail();

        $this->actingAs($secretaire)
            ->put('/user-stories/' . $story->id, [
                'title' => 'Story CRUD MAJ',
                'stream_id' => $stream->id,
                'status' => 'in_progress',
                'priority' => 'high',
            ])
            ->assertRedirect();

        $this->actingAs($secretaire)
            ->patch('/user-stories/' . $story->id . '/move', [
                'status' => 'done',
                'sort_order' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('user_stories', [
            'id' => $story->id,
            'title' => 'Story CRUD MAJ',
            'status' => 'done',
        ]);

        $this->actingAs($secretaire)
            ->delete('/user-stories/' . $story->id)
            ->assertForbidden();

        $this->actingAs($admin)
            ->delete('/user-stories/' . $story->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('user_stories', ['id' => $story->id]);
    }
}
