<?php

namespace App\Http\Requests\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
