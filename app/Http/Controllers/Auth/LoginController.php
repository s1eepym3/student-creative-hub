<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the maximum number of attempts to allow.
     */
    public function maxAttempts()
    {
        return 5;
    }

    /**
     * Get the number of minutes to throttle for.
     */
    public function decayMinutes()
    {
        return 1;
    }

    /**
     * The user has been authenticated.
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // Only log USER_LOGIN if active (others will be caught by middleware check status and booted out)
        if ($user->isActive()) {
            app(\App\Services\AuditLogService::class)->log(
                'USER_LOGIN',
                "User '{$user->name}' logged in successfully.",
                $user->id
            );
        } else {
            app(\App\Services\AuditLogService::class)->log(
                'USER_LOGIN_FAILED',
                "User '{$user->name}' attempted login but account is {$user->status}.",
                $user->id
            );
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        $user = $this->guard()->user();
        if ($user) {
            app(\App\Services\AuditLogService::class)->log(
                'USER_LOGOUT',
                "User '{$user->name}' logged out.",
                $user->id
            );
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
