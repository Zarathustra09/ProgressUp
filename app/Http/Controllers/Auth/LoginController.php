<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        $role = auth()->user()->role_id;

        switch ($role) {
            case 2:
                return route('home');
            case 3:
                return route('staffPages.home');
            default:
                auth()->logout();
                return route('login')->withErrors(['permission' => 'You do not have permission to access the website']);
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }


}
