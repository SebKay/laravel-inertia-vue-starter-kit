<?php

namespace App\Console\Commands;

use App\Services\PruneUnverifiedUsersService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;

#[Signature('app:prune-unverified {--dry-run : Preview stale unverified users without deleting them}')]
#[Description('Delete stale unverified users that are older than 30 days')]
class PruneUnverifiedUsersCommand extends Command
{
    public function handle(PruneUnverifiedUsersService $service): int
    {
        $results = $this->option('dry-run')
            ? $service->preview()
            : $service->prune();

        if ($results['users'] !== []) {
            table(
                headers: ['ID', 'Name', 'Email', 'Created At'],
                rows: array_map(
                    fn (array $user): array => [$user['id'], $user['name'], $user['email'], $user['created_at']],
                    $results['users'],
                ),
            );
        }

        $this->info("Matched {$results['matched_count']} stale unverified users.");

        if ($this->option('dry-run')) {
            $this->info('Dry run enabled; no users were deleted.');

            return self::SUCCESS;
        }

        $this->info("Deleted {$results['deleted_count']} stale unverified users.");

        return self::SUCCESS;
    }
}
