<?php

namespace App\Jobs;

use App\Enums\Role;
use App\Mail\RegisteredUserAlert;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRegisteredUserAlertToSuperAdmins implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $userId) {}

    public function handle(): void
    {
        $registeredUser = User::query()->find($this->userId);

        if (! $registeredUser) {
            return;
        }

        $payload = [
            'registered_user_name' => $registeredUser->name,
            'registered_user_email' => $registeredUser->email,
            'registered_at' => $registeredUser->created_at?->toDateTimeString() ?? now()->toDateTimeString(),
        ];

        User::role(Role::SUPER->value)
            ->get()
            ->each(function (User $superAdmin) use ($payload): void {
                Mail::to($superAdmin)->queue(new RegisteredUserAlert($payload));
            });
    }
}
