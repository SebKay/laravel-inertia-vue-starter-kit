<?php

namespace App\Console\Commands;

use App\Services\PruneUnverifiedUsersService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;

#[Signature('app:prune-unverified {--dry-run : Preview stale unverified users without deleting them}')]
#[Description('Delete stale unverified users that are older than 30 days')]
class PruneUnverifiedUsersCommand extends Command
{
    public function handle(PruneUnverifiedUsersService $service): int
    {
        $isDryRun = $this->option('dry-run');

        $results = spin(
            message: $isDryRun
                ? 'Checking for stale unverified users...'
                : 'Pruning stale unverified users...',
            callback: fn (): array => $isDryRun
                ? $service->preview()
                : $service->prune(),
        );

        if ($results['users'] !== []) {
            table(
                headers: ['ID', 'Name', 'Email', 'Created At'],
                rows: array_map(
                    fn (array $user): array => [$user['id'], $user['name'], $user['email'], $user['created_at']],
                    $results['users'],
                ),
            );
        }

        info($this->summaryMessage('Matched', $results['matched_count']));

        if ($isDryRun) {
            info('Dry run enabled. No users were deleted.');

            return self::SUCCESS;
        }

        info($this->summaryMessage('Deleted', $results['deleted_count']));

        return self::SUCCESS;
    }

    protected function summaryMessage(string $verb, int $count): string
    {
        return "{$verb} ".Str::plural('stale unverified user', $count, prependCount: true).'.';
    }
}
