<template>
    <h1 v-text="text" class="text-center text-3xl font-semibold"></h1>
</template>

<script setup lang="ts">
    defineProps({
        text: String,
    });
</script>
