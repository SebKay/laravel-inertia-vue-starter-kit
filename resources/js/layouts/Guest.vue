<template>
    <Head>
        <title></title>
    </Head>

    <div class="flex flex-col justify-center md:min-h-full">
        <main class="px-4 py-8 sm:px-6 xl:px-8 xl:py-16">
            <div class="mx-auto max-w-7xl">
                <SparklesIcon
                    class="text-ui-1-1 mx-auto mb-4 block size-10 xl:mb-8"
                />

                <div
                    v-if="heading"
                    class="mx-auto mb-4 max-w-2xl text-center xl:mb-8"
                >
                    <PageTitle :text="heading" />
                </div>

                <slot />
            </div>
        </main>
    </div>

    <Toaster />
    <Notice />
</template>

<script setup lang="ts">
    import type { LayoutProps } from "@js/types/inertia";

    import { Toaster } from "@/components/ui/sonner";
    import PageTitle from "@js/components/PageTitle.vue";
    import { Sparkles as SparklesIcon } from "lucide-vue-next";

    withDefaults(defineProps<LayoutProps>(), {
        heading: "",
    });
</script>
