<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // ❌ If user is not logged in
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // ❌ If user is not approved
        if ($user->status !== 'approved') {
            abort(403, 'Your account is not approved.');
        }

        // ❌ If role not allowed
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        // ✅ Allow request
        return $next($request);
    }
}