<template>
    <div v-if="heading" class="mb-4 xl:mb-8">
        <h1
            class="text-3xl font-medium text-neutral-900 xl:text-4xl"
            v-text="heading"
        ></h1>
    </div>
</template>

<script setup lang="ts">
    defineProps<{
        heading?: string;
    }>();
</script>
