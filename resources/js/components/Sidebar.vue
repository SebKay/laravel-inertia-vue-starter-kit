<template>
    <aside
        class="fixed top-0 left-0 z-40 flex h-full overflow-y-auto border-r bg-sidebar p-2.5 text-sidebar-foreground lg:w-72 lg:p-5"
        :class="!mobileMenuOpen ? 'max-lg:w-[60px]' : 'max-lg:w-72'"
    >
        <nav class="flex flex-1">
            <div class="lg:hidden">
                <Button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    type="button"
                    variant="outline"
                    size="icon"
                >
                    <span class="sr-only">Open main menu</span>
                    <MenuIcon class="block size-6" />
                </Button>
            </div>

            <div
                class="flex flex-1 flex-col max-lg:fixed max-lg:top-0 max-lg:left-0 max-lg:size-full max-lg:bg-black/50 max-lg:transition-opacity"
                :class="
                    !mobileMenuOpen
                        ? 'max-lg:pointer-events-none max-lg:opacity-0'
                        : 'max-lg:pointer-events-auto max-lg:opacity-100'
                "
            >
                <button
                    @click.prevent="mobileMenuOpen = false"
                    type="button"
                    class="absolute top-0 left-0 size-full lg:hidden"
                ></button>

                <div
                    class="flex flex-1 flex-col gap-5 max-lg:z-10 max-lg:w-72 max-lg:bg-sidebar max-lg:p-2.5 max-lg:pb-6 max-lg:transition-transform"
                    :class="!mobileMenuOpen ? 'max-lg:-translate-x-1/4' : ''"
                >
                    <div class="flex items-center">
                        <Link
                            :href="home()"
                            class="flex items-center gap-2.5 rounded-md px-2.5 py-2"
                        >
                            <SparklesIcon class="size-5 text-primary" />
                            <span class="text-sm font-semibold">
                                Laravel Starter Kit
                            </span>
                        </Link>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Button
                            as-child
                            variant="ghost"
                            class="w-full justify-start gap-2"
                            :class="navLinkActiveClass(['Dashboard/Index'])"
                        >
                            <Link
                                :href="home()"
                                prefetch
                                component="Dashboard/Index"
                            >
                                <LayoutDashboardIcon class="size-4 shrink-0" />
                                Dashboard
                            </Link>
                        </Button>

                        <Button
                            as-child
                            variant="ghost"
                            class="w-full justify-start gap-2"
                            :class="navLinkActiveClass(['Elements'])"
                        >
                            <Link
                                :href="elements()"
                                prefetch
                                component="Elements"
                            >
                                <LayoutListIcon class="size-4 shrink-0" />
                                Elements
                            </Link>
                        </Button>
                    </div>

                    <div class="mt-auto flex flex-col gap-5">
                        <div class="flex flex-col gap-1.5">
                            <Button
                                as-child
                                variant="ghost"
                                class="w-full justify-start gap-2"
                                :class="
                                    navLinkActiveClass([
                                        'Account/Edit',
                                        'EmailVerification/Show',
                                    ])
                                "
                            >
                                <Link
                                    :href="editAccount()"
                                    prefetch
                                    component="Account/Edit"
                                >
                                    <CircleUserIcon class="size-4 shrink-0" />
                                    Account
                                </Link>
                            </Button>

                            <Link
                                class="flex cursor-pointer items-center gap-2 rounded-md px-3 py-2 text-left text-sm font-medium text-muted-foreground transition-colors hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                                :href="LogoutController()"
                                method="post"
                                as="button"
                            >
                                <LogOutIcon class="size-4 shrink-0" />
                                Logout
                            </Link>
                        </div>

                        <p class="px-2.5 text-xs text-muted-foreground">
                            &copy; 2026
                            <a
                                href="https://sebkay.com/"
                                class="underline underline-offset-4 hover:opacity-80"
                                target="_blank"
                                >Seb Kay</a
                            >. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </nav>
    </aside>
</template>

<script setup lang="ts">
    import { ref, onMounted, watch } from "vue";
    import { router, usePage } from "@inertiajs/vue3";

    import { Button } from "@/components/ui/button";

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
    import { elements } from "@js/routes";
    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";

    import {
        Sparkles as SparklesIcon,
        Menu as MenuIcon,
        X as CloseIcon,
        CircleUserIcon,
        LogOutIcon,
        LayoutDashboardIcon,
        LayoutListIcon,
    } from "lucide-vue-next";

    const page = usePage();

    function navLinkActiveClass(components: string[]): Record<string, boolean> {
        const active = components.includes(String(page.component));

        return {
            "bg-sidebar-accent text-sidebar-accent-foreground": active,
        };
    }

    const mobileMenuOpen = ref(false);

    watch(mobileMenuOpen, (value) => {
        if (value) {
            document.querySelector("[data-main]")?.classList.add("active");
        } else {
            document.querySelector("[data-main]")?.classList.remove("active");
        }
    });

    onMounted(() => {
        router.on("success", () => {
            mobileMenuOpen.value = false;
        });
    });
</script>
