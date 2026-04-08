<template>
    <Head>
        <title></title>
    </Head>

    <div data-main class="min-h-dvh bg-background pl-[60px] lg:pl-72">
        <Sidebar />

        <main class="flex min-h-dvh bg-muted/40 p-2.5 lg:p-5">
            <div
                class="w-full flex-1 rounded-xl border bg-background shadow-xs"
            >
                <div class="p-6 lg:p-10">
                    <PageHeading :heading="heading" />

                    <slot />
                </div>
            </div>
        </main>

        <!-- <Footer class="mt-auto" /> -->
    </div>

    <Toaster />
    <Notice />
</template>

<script setup lang="ts">
    import { defineAsyncComponent } from "vue";

    import type { LayoutProps } from "@js/types/inertia";

    import { Toaster } from "@/components/ui/sonner";

    const Sidebar = defineAsyncComponent(
        () => import("@js/components/Sidebar.vue"),
    );
    const Footer = defineAsyncComponent(
        () => import("@js/components/Footer.vue"),
    );
    const PageHeading = defineAsyncComponent(
        () => import("@js/components/PageHeading.vue"),
    );

    withDefaults(defineProps<LayoutProps>(), {
        heading: "",
    });
</script>
