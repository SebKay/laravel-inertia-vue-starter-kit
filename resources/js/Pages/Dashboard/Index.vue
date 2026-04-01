<template>
    <Head :title="title" />

    <div class="rounded-2xl bg-white p-6 xl:p-10">Dashboard</div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps } from "@inertiajs/vue3";

    const title = "Dashboard";

    setLayoutProps({
        heading: title,
    });
</script>
