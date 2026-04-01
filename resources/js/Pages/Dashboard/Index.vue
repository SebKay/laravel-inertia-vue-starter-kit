<template>

    <Head :title="title" />

    <div class="bg-white rounded-2xl xl:p-10 p-6">
        Dashboard
    </div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps } from "@inertiajs/vue3";

    const title = "Dashboard";

    setLayoutProps({
        heading: title,
        subheading: "See the latest account activity and app overview as soon as you sign in.",
    });
</script>
