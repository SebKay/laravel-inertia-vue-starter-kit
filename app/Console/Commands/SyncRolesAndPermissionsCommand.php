<?php

namespace App\Console\Commands;

use App\Services\RolesAndPermissionsService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

#[Signature('app:permissions:sync {--fresh}')]
#[Description('Sync roles and permissions from enums to the database')]
class SyncRolesAndPermissionsCommand extends Command
{
    public function handle(RolesAndPermissionsService $service): int
    {
        $fresh = $this->option('fresh');

        if ($fresh) {
            info('Truncating existing roles and permissions...');
        }

        spin(
            message: 'Syncing roles and permissions...',
            callback: fn () => $service->sync($fresh),
        );

        info('Roles and permissions synced successfully.');

        return self::SUCCESS;
    }
}
