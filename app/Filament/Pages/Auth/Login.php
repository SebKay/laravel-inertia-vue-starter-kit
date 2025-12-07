<?php

namespace App\Filament\Pages\Auth;

use App\Enums\Environment;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        if (\app()->environment(Environment::LOCAL->value)) {
            $this->form->fill([
                'email' => \config('seed.users.super.email'),
                'password' => \config('seed.users.super.password'),
                'remember' => true,
            ]);
        }
    }
}
