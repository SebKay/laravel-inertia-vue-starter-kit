<script setup lang="ts">
    import { usePage, Link } from "@inertiajs/vue3";
    import type { Component } from "vue";

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
        components?: string[];
    }

    defineProps<{
        items: NavItem[];
    }>();

    const page = usePage();

    function isActive(item: NavItem): boolean {
        const current = String(page.component);

        return (item.components ?? []).includes(current);
    }
</script>

<template>
    <SidebarGroup>
        <SidebarGroupContent class="flex flex-col gap-2">
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton
                        :tooltip="item.title"
                        :is-active="isActive(item)"
                        as-child
                    >
                        <Link :href="item.url" prefetch>
                            <component :is="item.icon" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
