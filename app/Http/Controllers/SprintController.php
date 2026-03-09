<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\UserStory;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SprintController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Sprint::class);

        $sprints = Sprint::withCount([
            'userStories',
            'userStories as stories_done_count'        => fn ($q) => $q->where('status', 'done'),
            'userStories as stories_in_progress_count'  => fn ($q) => $q->where('status', 'in_progress'),
            'userStories as stories_todo_count'         => fn ($q) => $q->where('status', 'todo'),
        ])
            ->with(['userStories' => function ($q) {
                $q->with(['stream:id,name,color', 'persona:id,name,avatar_color', 'assignee:id,name'])
                  ->orderBy('sort_order');
            }])
            ->orderBy('number')
            ->get();

        $unassignedCount = UserStory::whereNull('sprint_id')->count();

        return Inertia::render('Sprints/Index', [
            'sprints'         => $sprints,
            'unassignedCount' => $unassignedCount,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Sprint::class);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'number'     => 'required|integer|min:1|unique:sprints,number',
            'goal'       => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'status'     => 'required|in:planned,active,completed',
        ]);

        $project = Project::first();
        $validated['project_id'] = $project?->id;

        $sprint = Sprint::create($validated);

        AuditLogger::log($request, 'sprint.created', $sprint, [
            'name' => $sprint->name,
            'number' => $sprint->number,
            'status' => $sprint->status,
        ]);

        return back();
    }

    public function update(Request $request, Sprint $sprint)
    {
        $this->authorize('update', $sprint);

        $validated = $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'number'     => "sometimes|required|integer|min:1|unique:sprints,number,{$sprint->id}",
            'goal'       => 'nullable|string|max:1000',
            'start_date' => 'sometimes|required|date',
            'end_date'   => 'sometimes|required|date|after:start_date',
            'status'     => 'sometimes|required|in:planned,active,completed',
        ]);

        $sprint->update($validated);

        AuditLogger::log($request, 'sprint.updated', $sprint, [
            'changes' => array_keys($validated),
        ]);

        return back();
    }

    public function destroy(Request $request, Sprint $sprint)
    {
        $this->authorize('delete', $sprint);

        $sprintId = $sprint->id;
        $sprintName = $sprint->name;
        // Unassign stories before deleting sprint
        $sprint->userStories()->update(['sprint_id' => null]);
        $sprint->delete();

        AuditLogger::log($request, 'sprint.deleted', null, [
            'id' => $sprintId,
            'name' => $sprintName,
        ]);

        return back();
    }
}
