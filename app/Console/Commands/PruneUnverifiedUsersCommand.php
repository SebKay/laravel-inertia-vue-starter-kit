<?php

namespace App\Console\Commands;

use App\Services\PruneUnverifiedUsersService;
use Illuminate\Console\Command;

class PruneUnverifiedUsersCommand extends Command
{
    protected $signature = 'users:prune-unverified {--dry-run : Preview stale unverified users without deleting them}';

    protected $description = 'Delete stale unverified users that are older than 30 days';

    public function handle(PruneUnverifiedUsersService $service): int
    {
        $results = $this->option('dry-run')
            ? $service->preview()
            : $service->prune();

        $this->info("Matched {$results['matched_count']} stale unverified users.");

        if ($this->option('dry-run')) {
            $this->info('Dry run enabled; no users were deleted.');

            return self::SUCCESS;
        }

        $this->info("Deleted {$results['deleted_count']} stale unverified users.");

        return self::SUCCESS;
    }
}
