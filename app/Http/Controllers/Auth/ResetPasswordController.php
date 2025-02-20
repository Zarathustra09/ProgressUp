<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected function redirectTo()
    {
        $user = auth()->user();
        if ($user->role_id == 2) {
            return route('home');
        } elseif ($user->role_id == 3) {
            return route('staffPages.home');
        } else {
            auth()->logout();
            return route('login')->withErrors(['permission' => 'You do not have permission to access the website']);
        }
    }
}
