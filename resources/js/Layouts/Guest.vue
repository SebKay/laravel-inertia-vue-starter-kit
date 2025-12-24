<template>

    <Head>
        <title></title>
    </Head>

    <div class="md:min-h-full flex flex-col justify-center">
        <main class="xl:py-16 py-8 px-4 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-7xl">
                <SparklesIcon class="size-10 mx-auto text-brand-800 block mb-4 xl:mb-8" />
                <slot />
            </div>
        </main>
    </div>

    <Notice />
</template>

<script setup lang="ts">
    import { Sparkles as SparklesIcon } from 'lucide-vue-next';
</script>
