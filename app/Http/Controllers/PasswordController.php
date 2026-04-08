<?php

namespace App\Http\Controllers;

use Inertia\Response;

class PasswordController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Password');
    }
}
