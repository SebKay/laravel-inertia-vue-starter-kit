<template>
    <Head>
        <title></title>
    </Head>

    <div class="lg:pl-64">
        <Sidebar class="absolute top-0 left-0 h-full w-64 max-lg:hidden" />

        <main class="flex min-h-screen lg:py-5 lg:pr-5">
            <div class="flex-1 rounded-xl bg-white">
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
