<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_search_page(): void
    {
        $this->get('/search?q=test')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_search_page(): void
    {
        $user = User::factory()->create(['role' => 'secretaire']);

        $this->actingAs($user)
            ->get('/search?q=test')
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Search/Index')
                ->where('q', 'test')
                ->has('results.users')
                ->has('results.stories')
                ->has('results.personas')
                ->has('results.streams')
            );
    }
}
