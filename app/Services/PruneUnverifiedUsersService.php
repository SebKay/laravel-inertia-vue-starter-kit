<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class PruneUnverifiedUsersService
{
    /**
     * Preview the number of stale unverified users eligible for pruning.
     *
     * @return array{
     *     matched_count: int,
     *     deleted_count: int,
     *     users: array<int, array{id: int, name: string, email: string, created_at: string}>
     * }
     */
    public function preview(): array
    {
        $users = $this->staleUsers();

        return [
            'matched_count' => count($users),
            'deleted_count' => 0,
            'users' => $users,
        ];
    }

    /**
     * Delete stale unverified users using model deletes so package cleanup hooks run.
     *
     * @return array{
     *     matched_count: int,
     *     deleted_count: int,
     *     users: array<int, array{id: int, name: string, email: string, created_at: string}>
     * }
     */
    public function prune(): array
    {
        $users = $this->staleUsers();
        $matchedCount = count($users);
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
            'users' => $users,
        ];
    }

    /**
     * @return array<int, array{id: int, name: string, email: string, created_at: string}>
     */
    protected function staleUsers(): array
    {
        return $this->staleUsersQuery()
            ->orderBy('id')
            ->get(['id', 'name', 'email', 'created_at'])
            ->map(fn (User $user): array => [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->toDateTimeString(),
            ])
            ->all();
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
