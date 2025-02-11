<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->hasPermission($permission)) {
            return $next($request);
        }

        // Redirect if the user is not an admin
        return response()->json(['error' => 'You do not have access to this action.'], 403);
    }
}
