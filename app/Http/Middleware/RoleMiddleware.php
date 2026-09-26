<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            // If unauthorized, redirect to their own dashboard with a flash message
            if (Auth::check()) {
                $redirectUrl = Auth::user()->isAdmin() ? '/admin/dashboard' : '/mahasiswa/dashboard';
                return redirect($redirectUrl)->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            
            return redirect('/login');
        }

        return $next($request);
    }
}
