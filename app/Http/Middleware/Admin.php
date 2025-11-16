<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in AND their role is 'admin'
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Redirect all non-admin users attempting to access admin routes
        return redirect('/')->with('error', 'Access Denied. You do not have administrator privileges.');
    }
}