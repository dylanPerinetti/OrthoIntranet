<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::withCount('userStories')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Personas/Index', [
            'personas' => $personas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'role'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'goals'        => 'nullable|string',
            'pain_points'  => 'nullable|string',
            'needs'        => 'nullable|string',
            'avatar_color' => 'nullable|string|max:20',
        ]);

        $validated['sort_order'] = Persona::max('sort_order') + 1;

        Persona::create($validated);

        return back()->with('success', 'Persona créé avec succès.');
    }

    public function update(Request $request, Persona $persona)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'role'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'goals'        => 'nullable|string',
            'pain_points'  => 'nullable|string',
            'needs'        => 'nullable|string',
            'avatar_color' => 'nullable|string|max:20',
        ]);

        $persona->update($validated);

        return back()->with('success', 'Persona modifié avec succès.');
    }

    public function destroy(Persona $persona)
    {
        $persona->delete();

        return back()->with('success', 'Persona supprimé.');
    }
}
