<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->isSuspended()) {
            auth()->guard()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Inertia::flash('toast', [
                'type' => 'warning',
                'message' => __('auth.suspended'),
            ]);

            return to_route('login');
        }

        return $next($request);
    }
}
