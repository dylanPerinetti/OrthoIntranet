<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Persona::class);

        $personas = Persona::withCount('userStories')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Personas/Index', [
            'personas' => $personas,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Persona::class);

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

        $persona = Persona::create($validated);

        AuditLogger::log($request, 'persona.created', $persona, [
            'name' => $persona->name,
            'role' => $persona->role,
        ]);

        return back()->with('success', 'Persona créé avec succès.');
    }

    public function update(Request $request, Persona $persona)
    {
        $this->authorize('update', $persona);

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

        AuditLogger::log($request, 'persona.updated', $persona, [
            'changes' => array_keys($validated),
        ]);

        return back()->with('success', 'Persona modifié avec succès.');
    }

    public function destroy(Request $request, Persona $persona)
    {
        $this->authorize('delete', $persona);

        $personaId = $persona->id;
        $personaName = $persona->name;
        $persona->delete();

        AuditLogger::log($request, 'persona.deleted', null, [
            'id' => $personaId,
            'name' => $personaName,
        ]);

        return back()->with('success', 'Persona supprimé.');
    }
}
