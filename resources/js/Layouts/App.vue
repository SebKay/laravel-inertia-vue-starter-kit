<template>

    <Head>
        <title></title>
    </Head>

    <div class="min-h-full flex flex-col">
        <Header :menu="menu" />

        <main class="xl:py-16 py-8 px-4 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-7xl">
                <slot />
            </div>
        </main>

        <Footer class="mt-auto" />
    </div>

    <Notice />
</template>

<script setup>
    import { computed, defineAsyncComponent } from "vue";

    import { index as home } from "@js/actions/App/Http/Controllers/DashboardController";
    import { edit as editAccount } from "@js/actions/App/Http/Controllers/AccountController";
    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";

    const Header = defineAsyncComponent(() => import("@js/Components/Header.vue"));
    const Footer = defineAsyncComponent(() => import("@js/Components/Footer.vue"));

    const menu = computed(() => {
        return [
            {
                label: "Dashboard",
                route: home(),
                condition: true,
                components: ['Dashboard/Index'],
            },
            {
                label: "Account",
                route: editAccount(),
                condition: true,
                components: ['Account/Edit', 'EmailVerification/Show'],
            },
            {
                label: "Logout",
                route: LogoutController(),
                method: "post",
                condition: true,
                components: [],
            },
        ];
    });
</script>
