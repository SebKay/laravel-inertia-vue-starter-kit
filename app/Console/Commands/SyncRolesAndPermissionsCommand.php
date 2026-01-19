<?php

namespace App\Console\Commands;

use App\Services\RolesAndPermissionsService;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class SyncRolesAndPermissionsCommand extends Command
{
    protected $signature = 'permissions:sync {--fresh}';

    protected $description = 'Sync roles and permissions from enums to the database';

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
