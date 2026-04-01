<template>
    <Head>
        <title></title>
    </Head>

    <div class="min-h-full flex flex-col">
        <Header :menu="menu" />

        <main class="xl:py-16 py-8 px-4 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-7xl" :class="contentClass">
                <div v-if="heading" class="xl:mb-8 mb-4">
                    <h1 class="xl:text-4xl text-3xl font-medium text-neutral-900" v-text="heading"></h1>

                    <p v-if="subheading" class="mt-2 max-w-2xl text-sm text-neutral-600" v-text="subheading"></p>
                </div>

                <slot />
            </div>
        </main>

        <Footer class="mt-auto" />
    </div>

    <Notice />
</template>

<script setup lang="ts">
    import { computed, defineAsyncComponent } from "vue";

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";
    import type { LayoutProps } from "@js/types/inertia";

    const Header = defineAsyncComponent(() => import("@js/Components/Header.vue"));
    const Footer = defineAsyncComponent(() => import("@js/Components/Footer.vue"));

    withDefaults(defineProps<LayoutProps>(), {
        heading: undefined,
        subheading: undefined,
        contentClass: "",
    });

    const menu = computed<{
        label: string;
        href: ReturnType<typeof home> | ReturnType<typeof editAccount> | ReturnType<typeof LogoutController>;
        condition: boolean;
        components: string[];
        prefetch?: true | 'mount' | 'hover' | 'click' | Array<'mount' | 'hover' | 'click'>;
        instantComponent?: string;
    }[]>(() => {
        return [
            {
                label: "Dashboard",
                href: home(),
                condition: true,
                components: ['Dashboard/Index'],
                prefetch: true,
                instantComponent: 'Dashboard/Index',
            },
            {
                label: "Account",
                href: editAccount(),
                condition: true,
                components: ['Account/Edit', 'EmailVerification/Show'],
                prefetch: true,
                instantComponent: 'Account/Edit',
            },
            {
                label: "Logout",
                href: LogoutController(),
                condition: true,
                components: [],
            },
        ];
    });
</script>
