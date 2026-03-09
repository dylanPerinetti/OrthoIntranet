<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Stream;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserStoryListController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', UserStory::class);

        $stories = UserStory::with([
            'stream:id,name,color',
            'persona:id,name,role,avatar_color',
            'assignee:id,name',
            'creator:id,name',
        ])
            ->orderByDesc('created_at')
            ->get();

        $streams  = Stream::orderBy('sort_order')->get(['id', 'name', 'color']);
        $personas = Persona::orderBy('sort_order')->get(['id', 'name', 'role', 'avatar_color']);
        $users    = User::orderBy('name')->get(['id', 'name', 'role']);

        return Inertia::render('UserStories/Index', [
            'stories'  => $stories,
            'streams'  => $streams,
            'personas' => $personas,
            'users'    => $users,
        ]);
    }
}
