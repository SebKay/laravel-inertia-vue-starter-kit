<template>
    <p
        v-if="message"
        class="field-error"
        v-text="message"
    ></p>
</template>

<script setup lang="ts">
    defineProps<{
        message?: string;
    }>();
</script>
