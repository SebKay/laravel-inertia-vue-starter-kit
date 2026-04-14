<script setup lang="ts">
    import AppSidebar from "@/components/AppSidebar.vue";
    import EmailVerificationBanner from "@/components/shell/EmailVerificationBanner.vue";
    import SiteHeader from "@/components/SiteHeader.vue";
    import { SidebarInset, SidebarProvider } from "@/components/ui/sidebar";
    import type { LayoutProps } from "@js/types/inertia";

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
        <SidebarInset
            class="bg-transparent md:peer-data-[variant=inset]:overflow-visible md:peer-data-[variant=inset]:rounded-none"
        >
            <div class="flex flex-1 flex-col">
                <div
                    class="*:data-email-verification-banner:-mb-4 *:data-email-verification-banner:rounded-b-none *:data-email-verification-banner:pb-4"
                >
                    <EmailVerificationBanner />
                </div>

                <div
                    class="flex flex-1 flex-col overflow-hidden rounded-xl bg-background"
                >
                    <SiteHeader :title="heading" />
                    <div class="flex flex-1 flex-col">
                        <div class="@container/main flex flex-1 flex-col">
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
