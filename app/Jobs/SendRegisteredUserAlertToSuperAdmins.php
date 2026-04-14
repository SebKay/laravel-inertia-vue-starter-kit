<?php

namespace App\Jobs;

use App\Enums\Role;
use App\Mail\RegisteredUserAlert;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Support\Facades\Mail;

#[DeleteWhenMissingModels]
class SendRegisteredUserAlertToSuperAdmins implements ShouldQueue
{
    use Queueable;

    public function __construct(
        #[WithoutRelations]
        public User $registeredUser,
    ) {}

    public function handle(): void
    {
        $payload = [
            'registered_user_name' => $this->registeredUser->name,
            'registered_user_email' => $this->registeredUser->email,
            'registered_at' => $this->registeredUser->created_at?->toDateTimeString() ?? now()->toDateTimeString(),
        ];

        User::role(Role::SUPER->value)
            ->get()
            ->each(function (User $superAdmin) use ($payload): void {
                Mail::to($superAdmin)->queue(new RegisteredUserAlert($payload));
            });
    }
}
