<template>
    <Head>
        <title></title>
    </Head>

    <div class="flex min-h-full flex-col">
        <Header />

        <main class="px-4 py-8 sm:px-6 xl:px-8 xl:py-16">
            <div class="mx-auto max-w-7xl" :class="contentClass">
                <div v-if="heading" class="mb-4 xl:mb-8">
                    <h1
                        class="text-3xl font-medium text-neutral-900 xl:text-4xl"
                        v-text="heading"
                    ></h1>

                    <p
                        v-if="subheading"
                        class="mt-2 max-w-2xl text-sm text-neutral-600"
                        v-text="subheading"
                    ></p>
                </div>

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

const Header = defineAsyncComponent(() => import("@js/Components/Header.vue"));
const Footer = defineAsyncComponent(() => import("@js/Components/Footer.vue"));

withDefaults(defineProps<LayoutProps>(), {
    heading: undefined,
    subheading: undefined,
    contentClass: "",
});
</script>
