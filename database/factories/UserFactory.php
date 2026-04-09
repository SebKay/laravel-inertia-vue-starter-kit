<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static string $password = 'password';

    /**
     * @return array{name: string, email: string, email_verified_at: Carbon, password: string, remember_token: string}
     */
    public function definition(): array
    {
        return [
            'name' => \fake()->name(),
            'email' => \fake()->unique()->safeEmail(),
            'email_verified_at' => \now(),
            'password' => static::$password,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function super(): static
    {
        return $this
            ->state(fn (array $attributes) => [
                'email' => config('seed.users.super.email'),
                'password' => config('seed.users.super.password'),
            ])
            ->afterCreating(function (User $user) {
                $user->assignRole(Role::SUPER);
            });
    }

    public function admin(): static
    {
        return $this
            ->state(fn (array $attributes) => [
                'email' => config('seed.users.admin.email'),
                'password' => config('seed.users.admin.password'),
            ])
            ->afterCreating(function (User $user) {
                $user->assignRole(Role::ADMIN);
            });
    }

    public function user(): static
    {
        return $this
            ->state(fn (array $attributes) => [
                'email' => config('seed.users.user.email'),
                'password' => config('seed.users.user.password'),
            ])
            ->afterCreating(function (User $user) {
                $user->assignRole(Role::USER);
            });
    }
}
