<template>
    <Head :title="title" />

    <div>Dashboard</div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps } from "@inertiajs/vue3";

    const title = "Dashboard";

    setLayoutProps({
        heading: title,
    });
</script>
