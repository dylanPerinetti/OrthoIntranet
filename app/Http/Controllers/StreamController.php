<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\UserStory;
use Inertia\Inertia;

class StreamController extends Controller
{
    public function show(Stream $stream)
    {
        $stream->loadCount('userStories');

        $stories = $stream->userStories()
            ->with(['assignee:id,name', 'creator:id,name'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        $statusCounts = [
            'todo'        => $stories->where('status', 'todo')->count(),
            'in_progress' => $stories->where('status', 'in_progress')->count(),
            'done'        => $stories->where('status', 'done')->count(),
        ];

        return Inertia::render('Streams/Show', [
            'stream'       => $stream,
            'stories'      => $stories,
            'statusCounts' => $statusCounts,
        ]);
    }
}
