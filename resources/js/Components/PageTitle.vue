<template>
    <h1 v-text="text" class="text-center h3 heading"></h1>
</template>

<script setup lang="ts">
    defineProps({
        text: String,
    });
</script>
