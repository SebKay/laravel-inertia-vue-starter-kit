<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\AccountUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $request->user()->update($request->safe()->only('name', 'email'));

        if (filled($request->validated('password'))) {
            $request->user()->update(['password' => $request->validated('password')]);
        }

        return Inertia::flash('success', __('account.updated'))->back();
    }
}
