<template>
    <h1
        v-text="text"
        class="heading h3 text-center"
    ></h1>
</template>

<script setup>
    const props = defineProps({
        text: String,
    });
</script>
