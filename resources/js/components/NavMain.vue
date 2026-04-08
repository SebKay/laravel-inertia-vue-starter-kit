<script setup lang="ts">
    import type { Component } from "vue";
    import { IconCirclePlusFilled, IconMail } from "@tabler/icons-vue";
    import { computed } from "vue";
    import { usePage, Link } from "@inertiajs/vue3";

    import { Button } from "@/components/ui/button";
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

    const props = defineProps<{
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
                <SidebarMenuItem class="flex items-center gap-2">
                    <SidebarMenuButton
                        tooltip="Quick Create"
                        class="min-w-8 bg-primary text-primary-foreground duration-200 ease-linear hover:bg-primary/90 hover:text-primary-foreground active:bg-primary/90 active:text-primary-foreground"
                    >
                        <IconCirclePlusFilled />
                        <span>Quick Create</span>
                    </SidebarMenuButton>
                    <Button
                        size="icon"
                        class="size-8 group-data-[collapsible=icon]:opacity-0"
                        variant="outline"
                    >
                        <IconMail />
                        <span class="sr-only">Inbox</span>
                    </Button>
                </SidebarMenuItem>
            </SidebarMenu>
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
