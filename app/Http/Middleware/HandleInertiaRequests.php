<?php

namespace App\Http\Middleware;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'loggedIn' => auth()->guard()->check(),
                'user' => $request->user() ? UserResource::make($request->user()) : [],
            ],
        ]);
    }
}
