<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        return inertia('Dashboard/Index', [
            'dashboard' => [
                'hero' => fn (): array => [
                    'name' => $request->user()?->name,
                    'email' => $request->user()?->email,
                    'emailVerified' => $request->user()?->hasVerifiedEmail() ?? false,
                    'permissions' => $request->user()?->all_permissions?->values()->all() ?? [],
                ],
                'stats' => Inertia::defer(fn (): array => [
                    'totalUsers' => User::query()->count(),
                    'verifiedUsers' => User::query()->whereNotNull('email_verified_at')->count(),
                    'newUsersLast30Days' => User::query()
                        ->where('created_at', '>=', now()->subDays(30))
                        ->count(),
                ], 'dashboard-stats'),
                'superAdmin' => Inertia::optional(fn (): ?array => $request->user()?->hasRole(Role::SUPER_ADMIN->value)
                    ? [
                        'privilegedUsers' => $this->privilegedUsersCount(),
                        'latestUsers' => User::query()
                            ->latest()
                            ->limit(5)
                            ->get(['id', 'name', 'email', 'created_at'])
                            ->map(fn (User $user): array => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'created_at' => $user->created_at?->toIso8601String(),
                            ])
                            ->all(),
                    ]
                    : null),
            ],
        ]);
    }

    private function privilegedUsersCount(): int
    {
        return User::query()
            ->hasRoles([Role::SUPER_ADMIN->value, Role::ADMIN->value])
            ->count();
    }
}
