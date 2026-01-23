<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\post;

describe('Users', function () {
    test('Can logout', function () {
        session()->regenerateToken();

        actingAs(User::factory()->create());

        $token = session()->token();
        session()->put('test', 'data');

        post(route('logout'))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('login');

        assertGuest();

        expect(session()->token())->not->toEqual($token);
        expect(session()->get('test'))->toBeNull();
    });
});

describe('Guests', function () {
    test("Can't logout", function () {
        post(route('logout'))
            ->assertSessionDoesntHaveErrors()
            ->assertRedirectToRoute('login');
    });
});
