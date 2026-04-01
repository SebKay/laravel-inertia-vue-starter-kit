<template>

    <Head :title="title" />

    <div class="bg-white rounded-2xl xl:p-10 p-6">
        Dashboard
    </div>
</template>

<script setup lang="ts">
    import { Head } from "@inertiajs/vue3";

    const title = "Dashboard";
</script>
