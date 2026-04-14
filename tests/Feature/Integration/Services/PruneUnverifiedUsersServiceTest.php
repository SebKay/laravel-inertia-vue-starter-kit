<?php

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\User;
use App\Services\PruneUnverifiedUsersService;

test('it previews and prunes stale unverified users', function () {
    $staleUnverifiedUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(31),
    ]);

    $freshUnverifiedUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(29),
    ]);

    $verifiedUser = User::factory()->create([
        'created_at' => now()->subDays(31),
    ]);

    $service = app(PruneUnverifiedUsersService::class);

    expect($service->preview())->toBe([
        'matched_count' => 1,
        'deleted_count' => 0,
    ]);

    expect($service->prune())->toBe([
        'matched_count' => 1,
        'deleted_count' => 1,
    ]);

    $this->assertModelMissing($staleUnverifiedUser);
    $this->assertModelExists($freshUnverifiedUser);
    $this->assertModelExists($verifiedUser);
});

test('it removes role and permission pivot records when pruning a stale user', function () {
    $staleUnverifiedUser = User::factory()->unverified()->create([
        'created_at' => now()->subDays(31),
    ]);

    $staleUnverifiedUser->assignRole(Role::ADMIN);
    $staleUnverifiedUser->givePermissionTo(Permission::EDIT_POSTS);

    $this->assertDatabaseHas('model_has_roles', [
        'model_type' => User::class,
        'model_id' => $staleUnverifiedUser->getKey(),
    ]);

    $this->assertDatabaseHas('model_has_permissions', [
        'model_type' => User::class,
        'model_id' => $staleUnverifiedUser->getKey(),
    ]);

    app(PruneUnverifiedUsersService::class)->prune();

    $this->assertModelMissing($staleUnverifiedUser);
    $this->assertDatabaseMissing('model_has_roles', [
        'model_type' => User::class,
        'model_id' => $staleUnverifiedUser->getKey(),
    ]);
    $this->assertDatabaseMissing('model_has_permissions', [
        'model_type' => User::class,
        'model_id' => $staleUnverifiedUser->getKey(),
    ]);
});
