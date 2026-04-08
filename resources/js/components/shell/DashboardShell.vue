<script setup lang="ts">
    import type { LayoutProps } from "@js/types/inertia";

    import AppSidebar from "@/components/AppSidebar.vue";
    import SiteHeader from "@/components/SiteHeader.vue";
    import { SidebarInset, SidebarProvider } from "@/components/ui/sidebar";

    withDefaults(defineProps<LayoutProps>(), {
        heading: "",
    });
</script>

<template>
    <SidebarProvider
        :style="{
            '--sidebar-width': 'calc(var(--spacing) * 72)',
            '--header-height': 'calc(var(--spacing) * 12)',
        }"
    >
        <AppSidebar variant="inset" />
        <SidebarInset>
            <SiteHeader :title="heading" />
            <div class="flex flex-1 flex-col">
                <div class="@container/main flex flex-1 flex-col gap-2">
                    <div class="flex flex-col gap-4 py-4 md:gap-6 md:py-6">
                        <slot />
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
