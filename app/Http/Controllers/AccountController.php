<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\AccountUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function edit(Request $request)
    {
        return inertia('Account/Edit', [
            'user' => UserResource::make($request->user()),
        ]);
    }

    public function update(AccountUpdateRequest $request)
    {
        $request->user()->update($request->only('name', 'email'));
        $request->user()->updatePassword($request->validated('password'));

        session()->flash('success', __('account.updated'));

        return redirect()->back();
    }
}
