<?php

use App\Enums\Queue;
use App\Enums\Role;
use App\Jobs\SendRegisteredUserAlertToSuperAdmins;
use App\Mail\RegisteredUserAlert;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('it queues one registered user alert per super admin on the mail queue', function () {
    Mail::fake();

    $registeredUser = User::factory()->create([
        'name' => 'Registered User',
        'email' => 'registered@example.com',
        'created_at' => now()->setTime(14, 30, 0),
    ]);
    $registeredUser->assignRole(Role::USER);

    $additionalSuperAdmin = User::factory()->create([
        'email' => 'second-super@example.com',
    ]);
    $additionalSuperAdmin->assignRole(Role::SUPER);

    $user = User::whereEmail(config('seed.users.user.email'))->firstOrFail();

    new SendRegisteredUserAlertToSuperAdmins($registeredUser->id)->handle();

    Mail::assertQueuedCount(2);
    Mail::assertQueued(RegisteredUserAlert::class, fn (RegisteredUserAlert $mail) => $mail->hasTo(superUser()->email)
        && $mail->queue === Queue::MAIL->value
        && $mail->payload['registered_user_name'] === $registeredUser->name
        && $mail->payload['registered_user_email'] === $registeredUser->email
        && $mail->payload['registered_at'] === $registeredUser->created_at->toDateTimeString());
    Mail::assertQueued(RegisteredUserAlert::class, fn (RegisteredUserAlert $mail) => $mail->hasTo($additionalSuperAdmin->email)
        && $mail->queue === Queue::MAIL->value);
    Mail::assertNotQueued(RegisteredUserAlert::class, fn (RegisteredUserAlert $mail): bool => $mail->hasTo(adminUser()->email));
    Mail::assertNotQueued(RegisteredUserAlert::class, fn (RegisteredUserAlert $mail): bool => $mail->hasTo($user->email));
});

test('it exits cleanly when there are no super admins', function () {
    Mail::fake();

    User::role(Role::SUPER->value)->get()->each(
        fn (User $superAdmin): User => tap($superAdmin)->syncRoles([])
    );

    $registeredUser = User::factory()->create();

    new SendRegisteredUserAlertToSuperAdmins($registeredUser->id)->handle();

    Mail::assertNothingQueued();
});

test('it exits cleanly when the registered user no longer exists', function () {
    Mail::fake();

    new SendRegisteredUserAlertToSuperAdmins(999_999)->handle();

    Mail::assertNothingQueued();
});
