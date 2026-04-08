<script setup lang="ts">
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
    import { userCan } from "@js/utilities/permissions";
    import { Link, usePage } from "@inertiajs/vue3";
    import { computed } from "vue";
    import type { PageProps } from "@js/types/inertia";

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";
    import { elements } from "@js/routes";

    import {
        LayoutDashboard as LayoutDashboardIcon,
        LayoutList as LayoutListIcon,
        CircleUser as CircleUserIcon,
        LogOut as LogOutIcon,
        Sparkles as SparklesIcon,
        Settings as SettingsIcon,
    } from "lucide-vue-next";

    const page = usePage();

    const showElementsLink = computed(() =>
        userCan(page.props as PageProps, "access-filament"),
    );

    const navItems = computed(() => {
        const items = [
            {
                title: "Dashboard",
                url: home(),
                icon: LayoutDashboardIcon,
                components: ["Dashboard/Index"],
            },
            {
                title: "Account",
                url: editAccount(),
                icon: CircleUserIcon,
                components: ["Account/Edit", "EmailVerification/Show"],
            },
        ];

        if (showElementsLink.value) {
            items.push({
                title: "Elements",
                url: elements(),
                icon: LayoutListIcon,
                components: ["Elements"],
            });
        }

        return items;
    });

    const userName = computed(
        () => page.props.auth?.user?.data.attributes.name ?? "Account",
    );
    const userEmail = computed(
        () => page.props.auth?.user?.data.attributes.email ?? "",
    );
</script>

<template>
    <Sidebar collapsible="offcanvas">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        as-child
                        class="data-[slot=sidebar-menu-button]:!p-1.5"
                    >
                        <a :href="home()">
                            <SparklesIcon class="!size-5 text-primary" />
                            <span class="text-base font-semibold"
                                >Starter Kit</span
                            >
                        </a>
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
                <SidebarMenuItem class="px-2 text-xs text-muted-foreground">
                    <span class="truncate">{{ userName }}</span>
                    <span v-if="userEmail" class="truncate">{{
                        userEmail
                    }}</span>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarFooter>
    </Sidebar>
</template>
