<template>
    <h1 v-text="text" class="h3 heading text-center"></h1>
</template>

<script setup lang="ts">
    defineProps({
        text: String,
    });
</script>
