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
        $request->user()->update($request->only('name', 'email'));

        if ($request->has('password')) {
            $request->user()->update(['password' => $request->string('password')]);
        }

        return Inertia::flash('success', __('account.updated'))->back();
    }
}
