<script setup lang="ts">
    import { Separator } from "@/components/ui/separator";
    import { SidebarTrigger } from "@/components/ui/sidebar";

    withDefaults(
        defineProps<{
            title?: string;
        }>(),
        {
            title: "",
        },
    );
</script>

<template>
    <header
        class="flex h-(--header-height) shrink-0 items-center gap-2 border-b bg-background transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-(--header-height)"
    >
        <div class="flex w-full items-center gap-1 px-4 lg:gap-2 lg:px-6">
            <SidebarTrigger class="-ml-1" />
            <Separator
                orientation="vertical"
                class="mx-2 data-[orientation=vertical]:h-4"
            />
            <h1 class="text-base font-medium">
                {{ title }}
            </h1>
        </div>
    </header>
</template>
