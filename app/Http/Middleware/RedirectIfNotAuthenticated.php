<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null $guard
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = 'web')
    {
        if (!$request->hasValidSignature()) {
            switch ($guard) {
                case 'admin':
                    $redirect_url = '/admin/login';
                    break;
                case 'myaccount':
                    $redirect_url = '/login/myaccount';
                    $guard = 'web';
                    break;
                default:
                    $redirect_url = '/welcome_back';
                    break;
            }

            if (!Auth::guard($guard)->check()) {
                return redirect($redirect_url);
            }
        }

        return $next($request);
    }
}
