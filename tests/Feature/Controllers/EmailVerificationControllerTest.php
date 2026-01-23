<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('Users', function () {
    test('Can access the verification page', function () {
        actingAs(User::factory()->unverified()->create())
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('EmailVerification/Show')
            );
    });

    test('Can verify their email address', function () {
        $user = User::factory()->unverified()->create();

        expect($user->verified_at)->toBeNull();

        actingAs($user)
            ->withoutMiddleware(Illuminate\Routing\Middleware\ValidateSignature::class)
            ->get(route('verification.verify', [
                'id' => $user->getKey(),
                'hash' => sha1((string) $user->getEmailForVerification()),
            ]))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('home');

        expect($user->refresh()->email_verified_at)->not()->toBeNull();
    });

    test('Can send the verificaiton notice', function () {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        actingAs($user)
            ->fromRoute('verification.notice')
            ->post(route('verification.send'))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('verification.notice');

        Notification::assertSentTo($user, Illuminate\Auth\Notifications\VerifyEmail::class);
    });
});

describe('Guests', function () {
    test("Can't access the verification page", function () {
        get(route('verification.notice'))
            ->assertRedirectToRoute('login');
    });
});
