<?php

namespace App\Http\Requests\ConfirmPassword;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPasswordStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }
}
