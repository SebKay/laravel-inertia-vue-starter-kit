<?php

use App\Enums\Queue;
use App\Mail\RegisteredUserAlert;

test('registered user alert uses the mail queue and renders registration details', function () {
    $payload = [
        'registered_user_name' => 'Jane Doe',
        'registered_user_email' => 'jane@example.com',
        'registered_at' => '2026-04-14 21:15:00',
    ];

    $mailable = new RegisteredUserAlert($payload);

    expect($mailable->queue)->toBe(Queue::MAIL->value)
        ->and($mailable->envelope()->subject)->toBe(sprintf('[%s] New user registered', config('app.name')))
        ->and($mailable->render())->toContain('New user registered')
        ->toContain('Jane Doe')
        ->toContain('jane@example.com')
        ->toContain('April 14, 2026 at 9:15 PM')
        ->not->toContain('2026-04-14 21:15:00');
});
