<template>
    <Head>
        <title></title>
    </Head>

    <div
        class="flex min-h-dvh flex-col justify-center bg-muted text-foreground"
    >
        <main class="px-4 py-8 sm:px-6 xl:px-8 xl:py-16">
            <div class="mx-auto max-w-7xl space-y-6 xl:space-y-10">
                <div>
                    <SparklesIcon
                        class="mx-auto mb-2.5 block size-10 text-primary xl:mb-5"
                    />

                    <div v-if="heading" class="mx-auto max-w-2xl text-center">
                        <PageTitle :text="heading" />
                    </div>
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
