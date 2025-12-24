<template>
    <h1
        v-text="text"
        class="heading h3 text-center"
    ></h1>
</template>

<script setup lang="ts">
    import { type PropType } from "vue";

    defineProps({
        text: String as PropType<string>,
    });
</script>
