<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Stream;
use App\Models\User;
use App\Models\UserStory;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $streams = Stream::withCount([
            'userStories',
            'userStories as stories_done_count'        => fn ($q) => $q->where('status', 'done'),
            'userStories as stories_in_progress_count'  => fn ($q) => $q->where('status', 'in_progress'),
            'userStories as stories_todo_count'         => fn ($q) => $q->where('status', 'todo'),
        ])->orderBy('sort_order')->get();

        $storyCounts = [
            'todo'        => UserStory::where('status', 'todo')->count(),
            'in_progress' => UserStory::where('status', 'in_progress')->count(),
            'done'        => UserStory::where('status', 'done')->count(),
        ];

        $recentStories = UserStory::with([
            'stream:id,name,color',
            'persona:id,name,avatar_color',
            'assignee:id,name',
        ])
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'status', 'priority', 'stream_id', 'persona_id', 'assigned_to', 'created_at']);

        return Inertia::render('Dashboard', [
            'streams'       => $streams,
            'storyCounts'   => $storyCounts,
            'recentStories' => $recentStories,
            'teamCount'     => User::count(),
            'personaCount'  => Persona::count(),
        ]);
    }
}
