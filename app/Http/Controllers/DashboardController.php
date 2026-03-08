<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\UserStory;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $streams = Stream::withCount('userStories')->orderBy('sort_order')->get();

        $storyCounts = [
            'todo'        => UserStory::where('status', 'todo')->count(),
            'in_progress' => UserStory::where('status', 'in_progress')->count(),
            'done'        => UserStory::where('status', 'done')->count(),
        ];

        return Inertia::render('Dashboard', [
            'streams'     => $streams,
            'storyCounts' => $storyCounts,
        ]);
    }
}
