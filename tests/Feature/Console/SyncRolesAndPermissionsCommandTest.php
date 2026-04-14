<?php

use App\Services\RolesAndPermissionsService;
use Mockery\MockInterface;

use function Pest\Laravel\mock;

it('delegates the default command path to the sync service', function () {
    mock(RolesAndPermissionsService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('sync')
            ->once()
            ->with(false);
    });

    $this->artisan('app:sync-permissions')
        ->expectsOutputToContain('Roles and permissions synced successfully.')
        ->assertSuccessful();
});

it('delegates the fresh command path to the sync service', function () {
    mock(RolesAndPermissionsService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('sync')
            ->once()
            ->with(true);
    });

    $this->artisan('app:sync-permissions --fresh')
        ->expectsOutputToContain('Truncating existing roles and permissions...')
        ->expectsOutputToContain('Roles and permissions synced successfully.')
        ->assertSuccessful();
});
