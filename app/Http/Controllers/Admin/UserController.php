<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::orderBy('name')
            ->get()
            ->map(fn (User $u) => [
                'id'        => $u->id,
                'name'      => $u->name,
                'email'     => $u->email,
                'role'      => $u->role,
                'role_name' => $u->roleName(),
                'created_at' => $u->created_at->format('d/m/Y'),
            ]);

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'roles' => User::ROLES,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:' . implode(',', array_keys(User::ROLES)),
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $created = User::create($validated);

        AuditLogger::log($request, 'admin.user.created', $created, [
            'email' => $created->email,
            'role' => $created->role,
        ]);

        return back()->with('success', 'Utilisateur créé avec succès.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:' . implode(',', array_keys(User::ROLES)),
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        AuditLogger::log($request, 'admin.user.updated', $user, [
            'changes' => array_keys($validated),
        ]);

        return back()->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $user, Request $request)
    {
        $this->authorize('delete', $user);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $deletedUser = ['id' => $user->id, 'email' => $user->email, 'name' => $user->name];

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        AuditLogger::log($request, 'admin.user.deleted', null, $deletedUser);

        return back()->with('success', 'Utilisateur supprimé.');
    }
}
