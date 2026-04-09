<?php

namespace App\Http\Requests\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => ['required', Password::defaults()],
            'password_confirmation' => ['required', 'same:password'],
        ];
    }
}
