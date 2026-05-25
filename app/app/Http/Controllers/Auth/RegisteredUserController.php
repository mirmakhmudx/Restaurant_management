<?php

namespace App\Http\Controllers\Auth;

use App\Enums\StaffRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'                  => ['required', Rule::in(['manager','waiter','chef','cashier'])],
            'password'              => ['required', 'confirmed', Rules\Password::min(8)],
        ], [
            'name.required'         => 'Full name is required.',
            'email.unique'          => 'This email is already registered.',
            'role.required'         => 'Please select a role.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => StaffRole::from($request->role),
            'is_active' => true,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route($user->getDashboardRoute())
            ->with('success', "Welcome to BitePlate, {$user->name}! 🎉");
    }
}
