<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Check if user is an admin (assuming 'role' column exists)
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Forbidden - Admins only'], 403);
        }

        return $next($request);
    }
}
