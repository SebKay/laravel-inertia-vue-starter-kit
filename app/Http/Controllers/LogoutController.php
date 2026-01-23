<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        auth()->guard()->logout();

        session()->regenerateToken();
        session()->invalidate();

        return to_route('login');
    }
}
