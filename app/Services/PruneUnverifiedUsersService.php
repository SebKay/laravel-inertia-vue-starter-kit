<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class PruneUnverifiedUsersService
{
    /**
     * Preview the number of stale unverified users eligible for pruning.
     *
     * @return array{matched_count: int, deleted_count: int}
     */
    public function preview(): array
    {
        return [
            'matched_count' => $this->staleUsersQuery()->count(),
            'deleted_count' => 0,
        ];
    }

    /**
     * Delete stale unverified users using model deletes so package cleanup hooks run.
     *
     * @return array{matched_count: int, deleted_count: int}
     */
    public function prune(): array
    {
        $matchedCount = $this->staleUsersQuery()->count();
        $deletedCount = 0;

        $this->staleUsersQuery()
            ->select('id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at')
            ->chunkById(100, function ($users) use (&$deletedCount): void {
                foreach ($users as $user) {
                    if ($user->delete()) {
                        $deletedCount++;
                    }
                }
            });

        return [
            'matched_count' => $matchedCount,
            'deleted_count' => $deletedCount,
        ];
    }

    /**
     * @return Builder<User>
     */
    protected function staleUsersQuery(): Builder
    {
        return User::query()
            ->whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays(30));
    }
}
