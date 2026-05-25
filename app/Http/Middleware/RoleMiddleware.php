<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role) {
            abort(403, 'You do not have access to this section.');
        }

        if (auth()->user()->status === 'suspended') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been suspended.']);
        }

        return $next($request);
    }
}
