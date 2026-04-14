<?php

use App\Enums\Role;
use App\Models\User;

it('deletes stale unverified users with the console command', function () {
    $staleUnverifiedUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(31),
    ]);

    $staleAdminUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(45),
    ]);
    $staleAdminUser->assignRole(Role::ADMIN);

    $freshUnverifiedUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(29),
    ]);

    $verifiedUser = User::factory()->create([
        'created_at' => now()->subDays(31),
    ]);

    $this->artisan('users:prune-unverified')
        ->expectsOutputToContain('Matched 2 stale unverified users.')
        ->expectsOutputToContain('Deleted 2 stale unverified users.')
        ->assertSuccessful();

    $this->assertModelMissing($staleUnverifiedUser);
    $this->assertModelMissing($staleAdminUser);
    $this->assertModelExists($freshUnverifiedUser);
    $this->assertModelExists($verifiedUser);
});

it('supports a dry run mode', function () {
    $staleUnverifiedUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(31),
    ]);

    $this->artisan('users:prune-unverified --dry-run')
        ->expectsOutputToContain('Matched 1 stale unverified users.')
        ->expectsOutputToContain('Dry run enabled; no users were deleted.')
        ->assertSuccessful();

    $this->assertModelExists($staleUnverifiedUser);
});

it('reports zero matches when no users are eligible for pruning', function () {
    User::factory()->unverified()->create([
        'created_at' => now()->subDays(29),
    ]);

    User::factory()->create([
        'created_at' => now()->subDays(31),
    ]);

    $this->artisan('users:prune-unverified')
        ->expectsOutputToContain('Matched 0 stale unverified users.')
        ->expectsOutputToContain('Deleted 0 stale unverified users.')
        ->assertSuccessful();
});
