<template>
    <Head :title="title" />

    <div class="space-y-6">
        <section class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
            <div class="bg-white rounded-2xl xl:p-10 p-6">
                <p class="text-sm font-medium text-brand-700">Welcome back</p>
                <h2 class="mt-2 text-2xl font-semibold text-neutral-950">
                    {{ dashboard.hero?.name ?? 'Your dashboard is loading...' }}
                </h2>
                <p class="mt-3 text-sm text-neutral-600">
                    {{ dashboard.hero?.email ?? 'Your account details will appear here in a moment.' }}
                </p>

                <div class="mt-6 flex flex-wrap gap-2">
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                        :class="dashboard.hero?.emailVerified ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'"
                    >
                        {{ dashboard.hero?.emailVerified ? 'Email verified' : 'Verification required' }}
                    </span>

                    <span class="inline-flex items-center rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-900">
                        {{ dashboard.hero?.permissions?.length ?? 0 }} permissions
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl xl:p-10 p-6">
                <h2 class="text-lg font-semibold text-neutral-950">Permission summary</h2>
                <ul class="mt-4 space-y-2 text-sm text-neutral-600">
                    <li v-for="permission in dashboard.hero?.permissions ?? []" :key="permission" class="rounded-xl bg-brand-50 px-3 py-2">
                        {{ permission }}
                    </li>
                    <li v-if="!(dashboard.hero?.permissions?.length)">No explicit permissions available yet.</li>
                </ul>
            </div>
        </section>

        <Deferred data="dashboard.stats">
            <template #fallback>
                <section class="grid gap-4 md:grid-cols-3">
                    <div v-for="card in 3" :key="card" class="animate-pulse rounded-2xl bg-white p-6">
                        <div class="h-4 w-24 rounded bg-brand-100"></div>
                        <div class="mt-4 h-8 w-16 rounded bg-brand-100"></div>
                    </div>
                </section>
            </template>

            <template #default="{ reloading }">
                <section class="grid gap-4 md:grid-cols-3" :class="{ 'opacity-70': reloading }">
                    <article class="rounded-2xl bg-white p-6">
                        <p class="text-sm text-neutral-500">Total users</p>
                        <p class="mt-3 text-3xl font-semibold text-neutral-950">{{ dashboard.stats?.totalUsers ?? 0 }}</p>
                    </article>

                    <article class="rounded-2xl bg-white p-6">
                        <p class="text-sm text-neutral-500">Verified users</p>
                        <p class="mt-3 text-3xl font-semibold text-neutral-950">{{ dashboard.stats?.verifiedUsers ?? 0 }}</p>
                    </article>

                    <article class="rounded-2xl bg-white p-6">
                        <p class="text-sm text-neutral-500">Joined in 30 days</p>
                        <p class="mt-3 text-3xl font-semibold text-neutral-950">{{ dashboard.stats?.newUsersLast30Days ?? 0 }}</p>
                    </article>
                </section>
            </template>
        </Deferred>

        <WhenVisible data="dashboard.superAdmin" :buffer="240">
            <template #fallback>
                <section v-if="userIsSuperAdmin" class="rounded-2xl bg-white p-6 animate-pulse">
                    <div class="h-5 w-40 rounded bg-brand-100"></div>
                    <div class="mt-4 h-24 rounded bg-brand-100"></div>
                </section>
            </template>

            <template #default="{ fetching }">
                <section v-if="dashboard.superAdmin" class="rounded-2xl bg-white xl:p-10 p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-neutral-950">Super admin overview</h2>
                            <p class="mt-1 text-sm text-neutral-500">Recent privileged activity and newest users.</p>
                        </div>

                        <button class="button" type="button" @click="reloadSuperAdmin" :disabled="fetching">
                            {{ fetching ? 'Refreshing...' : 'Refresh section' }}
                        </button>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,2fr)]">
                        <article class="rounded-2xl bg-brand-50 p-6">
                            <p class="text-sm text-neutral-500">Privileged users</p>
                            <p class="mt-3 text-3xl font-semibold text-neutral-950">{{ dashboard.superAdmin.privilegedUsers }}</p>
                        </article>

                        <article>
                            <h3 class="text-sm font-medium text-neutral-700">Newest users</h3>
                            <ul class="mt-4 space-y-3">
                                <li
                                    v-for="user in dashboard.superAdmin.latestUsers"
                                    :key="user.id"
                                    class="rounded-2xl border border-brand-100 px-4 py-3"
                                >
                                    <p class="font-medium text-neutral-900">{{ user.name }}</p>
                                    <p class="text-sm text-neutral-500">{{ user.email }}</p>
                                </li>
                            </ul>
                        </article>
                    </div>
                </section>
            </template>
        </WhenVisible>
    </div>
</template>

<script setup lang="ts">
    import { Deferred, Head, WhenVisible, router, setLayoutProps, usePage, usePoll } from "@inertiajs/vue3";
    import { computed } from "vue";

    import type { PageProps } from "@js/types/inertia";

    interface DashboardStats {
        totalUsers: number;
        verifiedUsers: number;
        newUsersLast30Days: number;
    }

    interface DashboardHero {
        name: string | null;
        email: string | null;
        emailVerified: boolean;
        permissions: string[];
    }

    interface DashboardSuperAdminUser {
        id: number;
        name: string;
        email: string;
        created_at: string | null;
    }

    interface DashboardSuperAdmin {
        privilegedUsers: number;
        latestUsers: DashboardSuperAdminUser[];
    }

    interface DashboardProps {
        dashboard: {
            hero?: DashboardHero;
            stats?: DashboardStats;
            superAdmin?: DashboardSuperAdmin | null;
        };
    }

    const title = "Dashboard";

    setLayoutProps({
        heading: title,
        subheading: "Live account and user metrics powered by Inertia v3 async loading.",
    });

    const page = usePage<PageProps<DashboardProps>>();

    const dashboard = computed(() => page.props.dashboard ?? {});
    const userIsSuperAdmin = computed(() => page.props.auth.user?.data.attributes.can.includes('access-filament') ?? false);

    usePoll(30000, {
        only: ['dashboard.stats'],
        preserveScroll: true,
    });

    const reloadSuperAdmin = () => {
        router.reload({
            only: ['dashboard.superAdmin'],
            preserveScroll: true,
        });
    };
</script>
