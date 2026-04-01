<template>
    <Head>
        <title></title>
    </Head>

    <div class="md:min-h-full flex flex-col justify-center">
        <main class="xl:py-16 py-8 px-4 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-7xl">
                <SparklesIcon class="size-10 mx-auto text-brand-800 block mb-4 xl:mb-8" />

                <div v-if="heading" class="mx-auto max-w-2xl mb-4 xl:mb-8 text-center">
                    <PageTitle :text="heading" />

                    <p v-if="subheading" class="mt-3 text-sm text-neutral-600" v-text="subheading"></p>
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
    import { Sparkles as SparklesIcon } from 'lucide-vue-next';

    withDefaults(defineProps<LayoutProps>(), {
        heading: undefined,
        subheading: undefined,
        contentClass: "",
    });
</script>
