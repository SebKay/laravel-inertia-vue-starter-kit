<template>
    <header class="bg-white px-4 sm:px-6 xl:px-8">
        <nav>
            <div class="mx-auto max-w-7xl">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <Link
                            class="shrink-0 text-brand-800"
                            :href="home()"
                        >
                        <SparklesIcon class="size-7" />
                        </Link>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <template
                                v-for="link in menu"
                                :key="link.label"
                            >
                                <Link
                                    v-if="link.condition"
                                    :href="link.route"
                                    :method="link?.method"
                                    :as="link?.method == 'post' ? 'button' : 'a'"
                                    v-text="link.label"
                                    class="rounded-xl px-3 py-2 text-sm font-medium cursor-pointer transition-colors"
                                    :class="{
                                        'bg-brand-100 text-brand-950': link.components.includes($page.component),
                                        'text-brand-600 hover:text-brand-950 focus:text-brand-950': !link.components.includes($page.component),
                                    }"
                                />
                            </template>
                        </div>
                    </div>

                    <div class="flex md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="relative inline-flex items-center justify-center rounded-md bg-brand-100 p-2 text-brand-900 hover:bg-brand-900 hover:text-white cursor-pointer"
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
                </div>
            </div>

            <div
                v-show="mobileMenuOpen"
                class="md:hidden"
            >
                <div class="space-y-1 pb-3 pt-2">
                    <template
                        v-for="link in menu"
                        :key="link.label"
                    >
                        <Link
                            v-if="link.condition"
                            :href="link.route"
                            v-text="link.label"
                            class="rounded-xl px-3 py-2 text-base font-medium block"
                            :class="{
                                'bg-brand-100 text-brand-950': link.components.includes($page.component),
                                'text-brand-600 focus:text-brand-950': !link.components.includes($page.component),
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
    import { ref, onMounted, type PropType } from "vue";
    import { router } from '@inertiajs/vue3';

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";

    import {
        Sparkles as SparklesIcon,
        Menu as MenuIcon,
        X as CloseIcon,
    } from 'lucide-vue-next';

    defineProps({
        menu: Array as PropType<{
            label: string;
            route: string;
            condition: boolean;
            components: string[];
            method?: string;
        }[]>,
    });

    const mobileMenuOpen = ref(false);

    onMounted(() => {
        router.on("success", () => {
            mobileMenuOpen.value = false;
        });
    });
</script>
