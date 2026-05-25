<?php

namespace App\Http\Middleware;

use App\Enums\StaffRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $allowedRoles = array_map(fn($r) => StaffRole::from($r), $roles);

        if (!$request->user()->hasAnyRole($allowedRoles)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
