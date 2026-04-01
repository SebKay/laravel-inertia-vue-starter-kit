<template>

    <Head :title="title" />

    <div class="mx-auto max-w-3xl">
        <div class="rounded-3xl bg-white px-6 py-12 shadow-sm sm:px-10">
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-brand-700">{{ status }}</p>
            <h1 class="mt-4 text-3xl font-semibold text-neutral-950 sm:text-4xl">{{ title }}</h1>
            <p class="mt-4 max-w-2xl text-sm text-neutral-600">{{ description }}</p>

            <div class="mt-8 flex flex-wrap gap-3">
                <Link
                    class="button"
                    :href="home()"
                    prefetch
                >Back to dashboard</Link>
                <Link
                    v-if="showLogin"
                    class="button"
                    :href="login()"
                    prefetch
                >Go to login</Link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { computed } from "vue";
    import { Head } from "@inertiajs/vue3";

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";

    import type { PageProps } from "@js/types/inertia";

    const props = defineProps<PageProps<{
        status: number;
    }>>();

    const messages: Record<number, { title: string; description: string }> = {
        403: {
            title: 'You do not have access to this page.',
            description: 'If you expected to see this page, check your role and permissions before trying again.',
        },
        404: {
            title: 'We could not find that page.',
            description: 'The page may have moved, or the link you followed may no longer be valid.',
        },
        419: {
            title: 'Your session has expired.',
            description: 'Refresh the page and try again. This usually happens after being idle for a while.',
        },
        500: {
            title: 'Something went wrong on our side.',
            description: 'The error has been recorded. Try again in a moment or head back to a stable page.',
        },
        503: {
            title: 'The app is temporarily unavailable.',
            description: 'We are likely deploying or performing maintenance. Please try again shortly.',
        },
    };

    const title = computed(() => messages[props.status]?.title ?? 'Unexpected error');
    const description = computed(() => messages[props.status]?.description ?? 'Please try again in a moment.');
    const showLogin = computed(() => Array.isArray(props.auth.user));
</script>
