<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || is_null($request->user()->email_verified_at)) {
            // Log the user out
            Auth::logout();

            alert()->warning('Please verify your email first');

            return Redirect::route('login');
        }

        // If email is verified, continue the request
        return $next($request);
    }
}
