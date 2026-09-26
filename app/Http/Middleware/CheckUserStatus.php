<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->isActive()) {
            $status = Auth::user()->status;
            
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $alertData = [
                'title' => 'Access Denied',
                'message' => 'Akun Anda sedang dinonaktifkan atau disuspensi. Silakan hubungi administrator.',
                'type' => 'error'
            ];

            if ($status === 'inactive') {
                $alertData = [
                    'title' => 'Account Not Yet Verified',
                    'message' => 'Your account is still waiting for Admin approval.<br>Please wait until your account is activated.',
                    'type' => 'info'
                ];
            } elseif ($status === 'suspended') {
                $alertData = [
                    'title' => 'Account Suspended',
                    'message' => 'Your account has been temporarily suspended.<br>Please contact the administrator for further information.',
                    'type' => 'error'
                ];
            }

            return redirect('/login')->with('swal_error', $alertData);
        }

        return $next($request);
    }
}
