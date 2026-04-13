<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EmailVerificationController extends Controller
{
    public function show(): Response
    {
        return inertia('EmailVerification/Show');
    }

    public function store(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return to_route('home');
    }

    public function update(Request $request): JsonResponse|SymfonyResponse
    {
        $request->user()->sendEmailVerificationNotification();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('account.verification-resent'),
            ]);
        }

        return Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('account.verification-resent'),
        ])->back();
    }
}
