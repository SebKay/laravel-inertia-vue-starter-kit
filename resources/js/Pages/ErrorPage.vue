<template>
    <Head :title="pageTitle" />

    <div class="flex min-h-screen items-center justify-center px-6 text-center">
        <div class="space-y-4 py-6">
            <p
                class="text-sm font-medium tracking-[0.2em] text-brand-700 uppercase"
            >
                {{ status }}
            </p>
            <h1 class="text-3xl font-semibold text-neutral-950 sm:text-4xl">
                {{ title }}
            </h1>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";
import Layout from "@js/Layouts/Bare.vue";

import type { PageProps } from "@js/types/inertia";

defineOptions({
    layout: Layout,
});

const props = defineProps<
    PageProps<{
        status: number;
    }>
>();

const messages: Record<number, { title: string }> = {
    403: {
        title: "You do not have access to this page",
    },
    404: {
        title: "We could not find that page",
    },
    419: {
        title: "Your session has expired",
    },
    500: {
        title: "Something went wrong on our side",
    },
    503: {
        title: "The app is temporarily unavailable",
    },
};

const pageTitle = computed(() => String(props.status));
const title = computed(
    () => messages[props.status]?.title ?? "Unexpected error",
);
</script>
