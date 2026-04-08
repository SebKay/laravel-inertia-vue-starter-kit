<template>
    <Head>
        <title></title>
    </Head>

    <DashboardShell :heading="heading">
        <slot />
    </DashboardShell>

    <Toaster />
    <Notice />
</template>

<script setup lang="ts">
    import DashboardShell from "@/components/shell/DashboardShell.vue";
    import { Toaster } from "@/components/ui/sonner";
    import type { LayoutProps } from "@js/types/inertia";

    withDefaults(defineProps<LayoutProps>(), {
        heading: "",
    });
</script>
