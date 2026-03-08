<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\UserStory;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserStoryController extends Controller
{
    public function index(Request $request)
    {
        $stories = UserStory::with(['stream:id,name,color', 'assignee:id,name', 'creator:id,name'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('status');

        $streams = Stream::orderBy('sort_order')->get(['id', 'name', 'color']);
        $users   = User::orderBy('name')->get(['id', 'name', 'role']);

        return Inertia::render('Backlog', [
            'columns' => [
                [
                    'key'   => 'todo',
                    'label' => 'À faire',
                    'color' => '#64748b',
                    'items' => $stories->get('todo', collect())->values(),
                ],
                [
                    'key'   => 'in_progress',
                    'label' => 'En cours',
                    'color' => '#3b82f6',
                    'items' => $stories->get('in_progress', collect())->values(),
                ],
                [
                    'key'   => 'done',
                    'label' => 'Terminé',
                    'color' => '#10b981',
                    'items' => $stories->get('done', collect())->values(),
                ],
            ],
            'streams' => $streams,
            'users'   => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'acceptance_criteria' => 'nullable|string',
            'stream_id'           => 'required|exists:streams,id',
            'sprint_id'           => 'nullable|exists:sprints,id',
            'assigned_to'         => 'nullable|exists:users,id',
            'status'              => 'required|in:todo,in_progress,done',
            'priority'            => 'required|in:low,medium,high',
            'story_points'        => 'nullable|integer|min:1|max:21',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['sort_order'] = UserStory::where('status', $validated['status'])->max('sort_order') + 1;

        UserStory::create($validated);

        return back();
    }

    public function update(Request $request, UserStory $userStory)
    {
        $validated = $request->validate([
            'title'               => 'sometimes|required|string|max:255',
            'description'         => 'nullable|string',
            'acceptance_criteria' => 'nullable|string',
            'stream_id'           => 'sometimes|required|exists:streams,id',
            'sprint_id'           => 'nullable|exists:sprints,id',
            'assigned_to'         => 'nullable|exists:users,id',
            'status'              => 'sometimes|required|in:todo,in_progress,done',
            'priority'            => 'sometimes|required|in:low,medium,high',
            'story_points'        => 'nullable|integer|min:1|max:21',
        ]);

        $userStory->update($validated);

        return back();
    }

    public function move(Request $request, UserStory $userStory)
    {
        $validated = $request->validate([
            'status'     => 'required|in:todo,in_progress,done',
            'sort_order' => 'required|integer|min:0',
        ]);

        $userStory->update($validated);

        return back();
    }

    public function destroy(UserStory $userStory)
    {
        $userStory->delete();

        return back();
    }
}
