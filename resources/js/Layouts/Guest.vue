<template>
    <Head>
        <title></title>
    </Head>

    <div class="flex flex-col justify-center md:min-h-full">
        <main class="px-4 py-8 sm:px-6 xl:px-8 xl:py-16">
            <div class="mx-auto max-w-7xl">
                <SparklesIcon
                    class="mx-auto mb-4 block size-10 text-ui-1-1 xl:mb-8"
                />

                <div
                    v-if="heading"
                    class="mx-auto mb-4 max-w-2xl text-center xl:mb-8"
                >
                    <PageTitle :text="heading" />

                    <p
                        v-if="subheading"
                        class="mt-3 text-sm"
                        v-text="subheading"
                    ></p>
                </div>

                <slot />
            </div>
        </main>
    </div>

    <Notice />
</template>

<script setup lang="ts">
    import type { LayoutProps } from "@js/types/inertia";

    import PageTitle from "@js/Components/PageTitle.vue";
    import { Sparkles as SparklesIcon } from "lucide-vue-next";

    withDefaults(defineProps<LayoutProps>(), {
        heading: undefined,
        subheading: undefined,
        contentClass: "",
    });
</script>
