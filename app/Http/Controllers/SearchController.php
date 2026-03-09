<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Stream;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->string('q'));

        if ($query === '') {
            return Inertia::render('Search/Index', [
                'q' => '',
                'results' => [
                    'users' => [],
                    'stories' => [],
                    'personas' => [],
                    'streams' => [],
                ],
            ]);
        }

        $users = User::query()
            ->select('id', 'name', 'email', 'role')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get();

        $stories = UserStory::query()
            ->with(['stream:id,name,slug', 'persona:id,name'])
            ->select('id', 'title', 'status', 'priority', 'stream_id', 'persona_id')
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        $personas = Persona::query()
            ->select('id', 'name', 'role')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('role', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(8)
            ->get();

        $streams = Stream::query()
            ->select('id', 'name', 'slug', 'color')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(8)
            ->get();

        return Inertia::render('Search/Index', [
            'q' => $query,
            'results' => [
                'users' => $users,
                'stories' => $stories,
                'personas' => $personas,
                'streams' => $streams,
            ],
        ]);
    }
}
