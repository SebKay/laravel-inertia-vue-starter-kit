<template>
    <header class="bg-white px-4 sm:px-6 xl:px-8">
        <nav>
            <div class="mx-auto max-w-7xl">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <Link class="shrink-0 text-brand-800" :href="home()">
                            <SparklesIcon class="size-7" />
                        </Link>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <template v-for="link in menu" :key="link.label">
                                <Link
                                    v-if="link.condition"
                                    :href="link.href"
                                    :prefetch="link.prefetch"
                                    :component="link.instantComponent"
                                    :as="
                                        link.href.method === 'post'
                                            ? 'button'
                                            : 'a'
                                    "
                                    v-text="link.label"
                                    class="cursor-pointer rounded-xl px-3 py-2 text-sm font-medium transition-colors"
                                    :class="{
                                        'bg-brand-100 text-brand-950':
                                            link.components.includes(
                                                $page.component,
                                            ),
                                        'text-brand-600 hover:text-brand-950 focus:text-brand-950':
                                            !link.components.includes(
                                                $page.component,
                                            ),
                                    }"
                                />
                            </template>
                        </div>
                    </div>

                    <div class="flex md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="relative inline-flex cursor-pointer items-center justify-center rounded-md bg-brand-100 p-2 text-brand-900 hover:bg-brand-900 hover:text-white"
                        >
                            <span class="sr-only">Open main menu</span>
                            <CloseIcon
                                v-if="mobileMenuOpen"
                                class="block size-6"
                            />
                            <MenuIcon v-else class="block size-6" />
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="mobileMenuOpen" class="md:hidden">
                <div class="space-y-1 pt-2 pb-3">
                    <template v-for="link in menu" :key="link.label">
                        <Link
                            v-if="link.condition"
                            :href="link.href"
                            :prefetch="link.prefetch"
                            :component="link.instantComponent"
                            :as="link.href.method === 'post' ? 'button' : 'a'"
                            v-text="link.label"
                            class="block rounded-xl px-3 py-2 text-base font-medium"
                            :class="{
                                'bg-brand-100 text-brand-950':
                                    link.components.includes($page.component),
                                'text-brand-600 focus:text-brand-950':
                                    !link.components.includes($page.component),
                            }"
                            aria-current="page"
                        />
                    </template>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";

import {
    Sparkles as SparklesIcon,
    Menu as MenuIcon,
    X as CloseIcon,
} from "lucide-vue-next";

const menu = computed<
    {
        label: string;
        href:
            | ReturnType<typeof home>
            | ReturnType<typeof editAccount>
            | ReturnType<typeof LogoutController>;
        condition: boolean;
        components: string[];
        prefetch?:
            | true
            | "mount"
            | "hover"
            | "click"
            | Array<"mount" | "hover" | "click">;
        instantComponent?: string;
    }[]
>(() => [
    {
        label: "Dashboard",
        href: home(),
        condition: true,
        components: ["Dashboard/Index"],
        prefetch: true,
        instantComponent: "Dashboard/Index",
    },
    {
        label: "Account",
        href: editAccount(),
        condition: true,
        components: ["Account/Edit", "EmailVerification/Show"],
        prefetch: true,
        instantComponent: "Account/Edit",
    },
    {
        label: "Logout",
        href: LogoutController(),
        condition: true,
        components: [],
    },
]);

const mobileMenuOpen = ref(false);

onMounted(() => {
    router.on("success", () => {
        mobileMenuOpen.value = false;
    });
});
</script>
