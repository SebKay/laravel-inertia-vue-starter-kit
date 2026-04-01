<template>
    <aside
        class="flex p-2.5 lg:p-5 fixed top-0 left-0 overflow-y-auto h-full lg:w-72"
        :class="!mobileMenuOpen ? 'max-lg:w-[60px]' : 'max-lg:w-72'"
    >
        <nav class="flex flex-1">
            <div class="lg:hidden">
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    type="button"
                    class="relative inline-flex cursor-pointer items-center justify-center rounded-lg bg-neutral-200 p-2 text-neutral-900 hover:bg-neutral-900 hover:text-white"
                >
                    <span class="sr-only">Open main menu</span>
                    <MenuIcon class="block size-6" />
                </button>
            </div>

            <div
                class="flex-1 flex flex-col max-lg:fixed max-lg:top-0 max-lg:left-0 max-lg:size-full"
                :class="!mobileMenuOpen ? 'max-lg:hidden' : ''"
            >
                <button
                    @click.prevent="mobileMenuOpen = false"
                    type="button"
                    class="lg:hidden absolute top-0 left-0 size-full bg-black/60"
                ></button>

                <div
                    class="flex flex-col gap-5 flex-1 max-lg:w-72 max-lg:bg-neutral-100 max-lg:p-2.5 max-lg:pb-6 max-lg:z-10">
                    <div class="flex items-center">
                        <Link
                            :href="home()"
                            class="flex items-center gap-2.5 p-2.5"
                        >
                        <SparklesIcon class="size-7 text-ui-1-1" />
                        <span class="font-semibold">Laravel Starter Kit</span>
                        </Link>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Link
                            :href="home()"
                            prefetch
                            component="Dashboard/Index"
                            class="flex cursor-pointer items-center gap-1.5 rounded-lg px-2.5 py-2 text-left text-sm leading-none font-medium transition-colors"
                            :class="navLinkActiveClass(['Dashboard/Index'])"
                        >
                        <LayoutDashboardIcon class="size-4 shrink-0" />
                        Dashboard
                        </Link>

                        <Link
                            :href="elements()"
                            prefetch
                            component="Elements"
                            class="flex cursor-pointer items-center gap-1.5 rounded-lg px-2.5 py-2 text-left text-sm leading-none font-medium transition-colors"
                            :class="navLinkActiveClass(['Elements'])"
                        >
                        <LayoutListIcon class="size-4 shrink-0" />
                        Elements
                        </Link>
                    </div>

                    <div class="mt-auto flex flex-col gap-5">
                        <div class="flex flex-col gap-1.5">
                            <Link
                                :href="editAccount()"
                                prefetch
                                component="Account/Edit"
                                class="flex cursor-pointer items-center gap-1.5 rounded-lg px-2.5 py-2 text-left text-sm leading-none font-medium transition-colors"
                                :class="navLinkActiveClass([
                                    'Account/Edit',
                                    'EmailVerification/Show',
                                ])
                                    "
                            >
                            <CircleUserIcon class="size-4 shrink-0" />
                            Account
                            </Link>

                            <Link
                                class="flex cursor-pointer items-center gap-1.5 rounded-lg px-2.5 py-2 text-left text-sm leading-none font-medium text-neutral-900/70 transition-colors hover:bg-neutral-200/75 hover:text-neutral-900"
                                :href="LogoutController()"
                                method="post"
                                as="button"
                            >
                            <LogOutIcon class="size-4 shrink-0" />
                            Logout
                            </Link>
                        </div>

                        <p class="px-2.5 text-xs text-neutral-900/70">
                            &copy; 2026
                            <a
                                href="https://sebkay.com/"
                                class="text-link"
                                target="_blank"
                            >Seb Kay</a>. All rights reserved.
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
            "bg-neutral-200/75 text-neutral-900": active,
            "text-neutral-900/70 hover:text-neutral-900 hover:bg-neutral-200/75":
                !active,
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
