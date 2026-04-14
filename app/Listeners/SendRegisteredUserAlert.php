<?php

namespace App\Listeners;

use App\Jobs\SendRegisteredUserAlertToSuperAdmins;
use Illuminate\Auth\Events\Registered;

class SendRegisteredUserAlert
{
    public function handle(Registered $event): void
    {
        SendRegisteredUserAlertToSuperAdmins::dispatch($event->user->getKey())
            ->afterCommit();
    }
}
