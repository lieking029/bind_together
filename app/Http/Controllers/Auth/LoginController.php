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
    protected function redirectTo()
    {
        // Check if the authenticated user has the 'student' role using Spatie's hasRole method
        if (auth()->user()->hasRole('student')) {
            return route('newsfeed.index');
        }

        // Default redirect to home if the user does not have the 'student' role
        return '/home';
    }


    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // Trigger SweetAlert after successful login
        alert()->success('Sign in successful', 'Redirecting...');

        // Continue with the default redirect behavior
        return redirect()->intended($this->redirectPath());
    }

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
}
