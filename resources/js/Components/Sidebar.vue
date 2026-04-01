<template>
    <aside class="p-5 flex">
        <nav class="flex flex-col gap-5 flex-1">
            <div class="flex items-center">
                <Link
                    :href="home()"
                    class="flex items-center gap-2.5 p-2.5"
                >
                <SparklesIcon class="size-7 text-ui-1-1" />
                <span class="font-semibold"> Laravel Starter Kit </span>
                </Link>
            </div>

            <div class="hidden">
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    type="button"
                    class="relative inline-flex cursor-pointer items-center justify-center rounded-lg bg-neutral-100 p-2 text-neutral-900 hover:bg-neutral-900 hover:text-white"
                >
                    <span class="sr-only">Open main menu</span>
                    <CloseIcon
                        v-if="mobileMenuOpen"
                        class="block size-6"
                    />
                    <MenuIcon
                        v-else
                        class="block size-6"
                    />
                </button>
            </div>

            <div class="flex flex-col gap-1.5">
                <Link
                    :href="home()"
                    prefetch
                    component="Dashboard/Index"
                    class="cursor-pointer rounded-lg px-2.5 py-1.5 text-left text-sm font-medium transition-colors"
                    :class="navLinkActiveClass(['Dashboard/Index'])"
                >
                Dashboard
                </Link>
                <Link
                    :href="elements()"
                    prefetch
                    component="Elements"
                    class="cursor-pointer rounded-lg px-2.5 py-1.5 text-left text-sm font-medium transition-colors"
                    :class="navLinkActiveClass(['Elements'])"
                >
                Elements
                </Link>
            </div>

            <div class="mt-auto flex flex-col gap-5">
                <div class="flex flex-col gap-1.5">
                    <Link
                        :href="editAccount()"
                        prefetch
                        component="Account/Edit"
                        class="cursor-pointer rounded-lg px-2.5 py-1.5 text-left text-sm font-medium transition-colors"
                        :class="navLinkActiveClass([
                            'Account/Edit',
                            'EmailVerification/Show',
                        ])
                            "
                    >
                    Account
                    </Link>
                    <Link
                        class="cursor-pointer rounded-lg px-2.5 py-1.5 text-left text-sm font-medium transition-colors text-neutral-900/70 hover:bg-neutral-200/75 hover:text-neutral-900"
                        :href="LogoutController()"
                        method="post"
                        as="button"
                    >
                    Logout
                    </Link>
                </div>

                <p class="text-xs text-neutral-900/70 px-2.5">
                    &copy; 2026
                    <a
                        href="https://sebkay.com/"
                        class="text-link"
                        target="_blank"
                    >Seb Kay</a>.
                    All rights reserved.
                </p>
            </div>
        </nav>
    </aside>
</template>

<script setup lang="ts">
    import { ref, onMounted } from "vue";
    import { router, usePage } from "@inertiajs/vue3";

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
    import { elements } from "@js/routes";
    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";

    import {
        Sparkles as SparklesIcon,
        Menu as MenuIcon,
        X as CloseIcon,
    } from "lucide-vue-next";

    const page = usePage();

    function navLinkActiveClass(components: string[]): Record<string, boolean> {
        const active = components.includes(String(page.component));

        return {
            "bg-neutral-200/75 text-neutral-900": active,
            "text-neutral-900/70 hover:bg-neutral-200/75": !active,
        };
    }

    const mobileMenuOpen = ref(false);

    onMounted(() => {
        router.on("success", () => {
            mobileMenuOpen.value = false;
        });
    });
</script>
