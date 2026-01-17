<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => \fake()->name(),
            'email' => \fake()->unique()->safeEmail(),
            'email_verified_at' => \now(),
            'password' => Hash::make(config('seed.users.super.password')),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function superAdmin(?string $email = null)
    {
        return $this
            ->state(fn (array $attributes) => [
                'email' => config('seed.users.super.email'),
                'password' => Hash::make(config('seed.users.super.password')),
            ])
            ->afterCreating(function (User $user) {
                $user->assignRole(Role::SUPER_ADMIN);
            });
    }

    public function admin()
    {
        return $this
            ->state(fn (array $attributes) => [
                'email' => config('seed.users.admin.email'),
                'password' => Hash::make(config('seed.users.admin.password')),
            ])
            ->afterCreating(function (User $user) {
                $user->assignRole(Role::ADMIN);
            });
    }

    public function user()
    {
        return $this
            ->state(fn (array $attributes) => [
                'email' => config('seed.users.user.email'),
                'password' => Hash::make(config('seed.users.user.password')),
            ])
            ->afterCreating(function (User $user) {
                $user->assignRole(Role::USER);
            });
    }
}
