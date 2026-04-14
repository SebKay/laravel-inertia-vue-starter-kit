<?php

use App\Enums\Queue;
use App\Enums\Role;
use App\Jobs\SendRegisteredUserAlertToSuperAdmins;
use App\Mail\RegisteredUserAlert;
use App\Models\User;
use Illuminate\Contracts\Queue\Job as QueueJob;
use Illuminate\Queue\CallQueuedHandler;
use Illuminate\Support\Facades\Mail;
use Mockery\MockInterface;

use function Pest\Laravel\mock;

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

    new SendRegisteredUserAlertToSuperAdmins($registeredUser)->handle();

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

    new SendRegisteredUserAlertToSuperAdmins($registeredUser)->handle();

    Mail::assertNothingQueued();
});

test('it is deleted when the registered user model is missing from the queue payload', function () {
    $registeredUser = User::factory()->create();

    $serializedJob = serialize(new SendRegisteredUserAlertToSuperAdmins($registeredUser));

    $registeredUser->delete();

    $queueJob = mock(QueueJob::class, function (MockInterface $mock): void {
        $mock->shouldReceive('payload')
            ->atLeast()
            ->once()
            ->andReturn(['deleteWhenMissingModels' => true]);
        $mock->shouldReceive('resolveQueuedJobClass')
            ->once()
            ->andReturn(SendRegisteredUserAlertToSuperAdmins::class);
        $mock->shouldReceive('delete')
            ->once();
    });

    app(CallQueuedHandler::class)->call($queueJob, ['command' => $serializedJob]);
});
