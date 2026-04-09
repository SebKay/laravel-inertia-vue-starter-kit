<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'sometimes', 'string', 'max:255'],
            'email' => ['required', 'sometimes', 'email', 'unique:users,email,'.auth()->guard()->id()],
            'password' => ['nullable', Password::defaults()],
        ];
    }
}
