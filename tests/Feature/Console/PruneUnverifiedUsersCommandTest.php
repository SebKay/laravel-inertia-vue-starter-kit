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

    $this->artisan('app:prune-unverified')
        ->expectsPromptsTable(
            headers: ['ID', 'Name', 'Email', 'Created At'],
            rows: [
                [
                    $staleUnverifiedUser->getKey(),
                    $staleUnverifiedUser->name,
                    $staleUnverifiedUser->email,
                    $staleUnverifiedUser->created_at->toDateTimeString(),
                ],
                [
                    $staleAdminUser->getKey(),
                    $staleAdminUser->name,
                    $staleAdminUser->email,
                    $staleAdminUser->created_at->toDateTimeString(),
                ],
            ],
        )
        ->expectsPromptsInfo('Matched 2 stale unverified users.')
        ->expectsPromptsInfo('Deleted 2 stale unverified users.')
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

    $this->artisan('app:prune-unverified --dry-run')
        ->expectsPromptsTable(
            headers: ['ID', 'Name', 'Email', 'Created At'],
            rows: [[
                $staleUnverifiedUser->getKey(),
                $staleUnverifiedUser->name,
                $staleUnverifiedUser->email,
                $staleUnverifiedUser->created_at->toDateTimeString(),
            ]],
        )
        ->expectsPromptsInfo('Matched 1 stale unverified users.')
        ->expectsPromptsInfo('Dry run enabled; no users were deleted.')
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

    $this->artisan('app:prune-unverified')
        ->expectsPromptsInfo('Matched 0 stale unverified users.')
        ->expectsPromptsInfo('Deleted 0 stale unverified users.')
        ->assertSuccessful();
});
