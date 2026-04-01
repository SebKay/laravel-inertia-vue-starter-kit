<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return inertia('EmailVerification/Show');
    }

    public function store(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return to_route('home');
    }

    public function update(Request $request): JsonResponse|Response
    {
        $request->user()->sendEmailVerificationNotification();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('account.verification-resent'),
            ]);
        }

        return Inertia::flash('success', __('account.verification-resent'))->back();
    }
}
