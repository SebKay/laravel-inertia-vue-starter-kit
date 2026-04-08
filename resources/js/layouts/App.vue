<template>
    <Head>
        <title></title>
    </Head>

    <div data-main class="pl-[60px] lg:pl-72">
        <Sidebar />

        <main class="flex min-h-screen py-2.5 pr-2.5 lg:py-5 lg:pr-5">
            <div class="w-full flex-1 rounded-xl bg-white">
                <div class="p-10">
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
