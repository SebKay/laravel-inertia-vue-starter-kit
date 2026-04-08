<script setup lang="ts">
    import { Link, usePage } from "@inertiajs/vue3";
    import {
        LayoutDashboard as LayoutDashboardIcon,
        LayoutList as LayoutListIcon,
        LogOut as LogOutIcon,
        Sparkles as SparklesIcon,
        Settings as SettingsIcon,
    } from "lucide-vue-next";
    import { computed } from "vue";
    import NavMain from "@/components/NavMain.vue";
    import {
        Sidebar,
        SidebarContent,
        SidebarFooter,
        SidebarHeader,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
    } from "@/components/ui/sidebar";
    import type { SidebarProps } from "@/components/ui/sidebar";
    import { toUrl } from "@/lib/utils";

    import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";
    import PasswordController from "@js/actions/App/Http/Controllers/PasswordController";
    import type { PageProps } from "@js/types/inertia";
    import { userCan } from "@js/utilities/permissions";

    const page = usePage();

    const showElementsLink = computed(() =>
        userCan(page.props as unknown as PageProps, "access-filament"),
    );

    const navItems = computed(() => {
        const items = [
            {
                title: "Dashboard",
                url: toUrl(home()),
                icon: LayoutDashboardIcon,
                components: ["Dashboard/Index"],
            },
        ];

        if (showElementsLink.value) {
            items.push({
                title: "Password Test",
                url: toUrl(PasswordController()),
                icon: LayoutListIcon,
                components: ["Password"],
            });
        }

        return items;
    });

    withDefaults(
        defineProps<{
            variant?: SidebarProps["variant"];
        }>(),
        {
            variant: "sidebar",
        },
    );
</script>

<template>
    <Sidebar collapsible="offcanvas" :variant="variant">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        as-child
                        class="hover:bg-transparent active:bg-transparent data-[slot=sidebar-menu-button]:p-0!"
                    >
                        <Link :href="home()" prefetch>
                            <SparklesIcon class="size-5! text-primary" />
                            <span class="text-base font-semibold"
                                >Starter Kit</span
                            >
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="navItems" />
        </SidebarContent>
        <SidebarFooter>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton as-child>
                        <Link :href="editAccount()" prefetch>
                            <SettingsIcon class="size-4" />
                            <span>Account</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                <SidebarMenuItem>
                    <SidebarMenuButton as-child>
                        <Link
                            :href="LogoutController()"
                            method="post"
                            as="button"
                        >
                            <LogOutIcon class="size-4" />
                            <span>Logout</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                <SidebarMenuItem
                    class="mt-4 border-t border-sidebar-border/50 px-2 pt-4 text-xs text-muted-foreground"
                >
                    <span>
                        &copy; 2026
                        <a
                            class="underline underline-offset-4 hover:opacity-80"
                            href="https://sebkay.com/"
                            target="_blank"
                            rel="noopener noreferrer"
                            >Seb Kay</a
                        >. All rights reserved.
                    </span>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarFooter>
    </Sidebar>
</template>
