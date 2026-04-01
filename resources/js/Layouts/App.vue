<template>
    <Head>
        <title></title>
    </Head>

    <div data-main class="pl-[60px] lg:pl-72">
        <Sidebar />

        <main class="flex min-h-screen py-2.5 pr-2.5 lg:py-5 lg:pr-5">
            <div class="flex-1 w-full rounded-xl bg-white">
                <div class="p-10">
                    <PageHeading :heading="heading" />

                    <slot />
                </div>
            </div>
        </main>

        <!-- <Footer class="mt-auto" /> -->
    </div>

    <Notice />
</template>

<script setup lang="ts">
    import { defineAsyncComponent } from "vue";

    import type { LayoutProps } from "@js/types/inertia";

    const Sidebar = defineAsyncComponent(
        () => import("@js/Components/Sidebar.vue"),
    );
    const Footer = defineAsyncComponent(
        () => import("@js/Components/Footer.vue"),
    );
    const PageHeading = defineAsyncComponent(
        () => import("@js/Components/PageHeading.vue"),
    );

    withDefaults(defineProps<LayoutProps>(), {
        heading: "",
    });
</script>
