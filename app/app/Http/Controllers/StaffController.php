<?php

namespace App\Http\Controllers;

use App\Enums\StaffRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $query = User::orderByRaw("is_active DESC")->orderBy('name');

        if ($filter === 'pending') {
            $query->where('is_active', false);
        } elseif ($filter !== 'all') {
            $query->where('role', $filter);
        }

        $staff   = $query->get();
        $pending = User::where('is_active', false)->count();
        $roles   = StaffRole::cases();

        return view('staff.index', compact('staff', 'filter', 'pending', 'roles'));
    }

    public function toggleActive(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot change your own status.');

        $user->update(['is_active' => !$user->is_active]);

        $action = $user->is_active ? 'approved' : 'deactivated';

        return back()->with('success', "{$user->name} has been {$action}.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot change your own role.');

        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', "{$user->name}'s role updated to {$user->fresh()->role->label()}.");
    }
}
