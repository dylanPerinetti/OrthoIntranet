<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Register', [
            'roles' => collect(User::ROLES)->except(User::ROLE_ADMIN)->map(fn ($label, $key) => [
                'value' => $key,
                'label' => $label,
            ])->values(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => 'required|in:secretaire,assistante',
        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->intended('/');
    }
}
