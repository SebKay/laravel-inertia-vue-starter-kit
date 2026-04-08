<script setup lang="ts">
    import type { Component } from "vue";
    import { computed } from "vue";
    import { Link, usePage } from "@inertiajs/vue3";
    import { toUrl } from "@/lib/utils";

    import {
        SidebarGroup,
        SidebarGroupContent,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
    } from "@/components/ui/sidebar";

    interface NavItem {
        title: string;
        url: string;
        icon?: Component;
    }

    defineProps<{
        items: NavItem[];
    }>();

    const page = usePage();

    const currentPath = computed(() => {
        const url = String(page.url ?? "");
        return url.split("?")[0] ?? url;
    });

    function isActive(item: NavItem): boolean {
        const itemPath = String(toUrl(item.url)).split("?")[0];
        return currentPath.value === itemPath;
    }
</script>

<template>
    <SidebarGroup>
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton :is-active="isActive(item)" as-child>
                        <Link :href="item.url" prefetch>
                            <component :is="item.icon" v-if="item.icon" />
                            {{ item.title }}
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
