<template>
    <Head>
        <title></title>
    </Head>

    <div class="flex min-h-full flex-col">
        <Header />

        <main class="px-4 py-8 sm:px-6 xl:px-8 xl:py-16">
            <div class="mx-auto max-w-7xl" :class="contentClass">
                <PageHeading :heading="heading" />

                <slot />
            </div>
        </main>

        <Footer class="mt-auto" />
    </div>

    <Notice />
</template>

<script setup lang="ts">
    import { defineAsyncComponent } from "vue";

    import type { LayoutProps } from "@js/types/inertia";

    const Header = defineAsyncComponent(
        () => import("@js/Components/Header.vue"),
    );
    const Footer = defineAsyncComponent(
        () => import("@js/Components/Footer.vue"),
    );
    const PageHeading = defineAsyncComponent(
        () => import("@js/Components/PageHeading.vue"),
    );

    withDefaults(defineProps<LayoutProps>(), {
        heading: "",
        contentClass: "",
    });
</script>
