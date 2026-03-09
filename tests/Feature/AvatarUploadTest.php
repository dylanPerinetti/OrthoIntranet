<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'secretaire']);

        $this->actingAs($user)
            ->post('/settings/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_uploading_new_avatar_deletes_old_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'secretaire']);

        $this->actingAs($user)->post('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('first.jpg', 200, 200),
        ]);

        $user->refresh();
        $firstPath = $user->avatar;

        $this->actingAs($user)->post('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('second.jpg', 200, 200),
        ])->assertRedirect();

        $user->refresh();
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_avatar_validation_rejects_non_image_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'secretaire']);

        $this->actingAs($user)
            ->post('/settings/avatar', [
                'avatar' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('avatar');
    }
}
