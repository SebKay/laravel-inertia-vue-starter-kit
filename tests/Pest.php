<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(Tests\TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        $this->withoutVite();
        $this->artisan('db:seed --class=TestsSeeder');
    })
    ->in('Feature');

uses(Tests\TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        $this->withoutVite();
    })
    ->in('Architecture', 'Integration');

function superAdminUser()
{
    return User::whereEmail(\config('seed.users.super.email'))->first();
}

function adminUser()
{
    return User::whereEmail(\config('seed.users.admin.email'))->first();
}
