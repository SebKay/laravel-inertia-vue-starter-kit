<template>

    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <PageTitle
            class="mb-4 xl:mb-8"
            :text="title"
        />

        <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
            <div class="text-center">
                <p>
                    Please verify your email address by clicking on the link we just emailed to you.
                </p>
                <button
                    class="button mt-6"
                    @click="resend"
                >
                    Resend Verification Email
                </button>
            </div>
        </div>

        <div class="mt-6 xl:mt-10">
            <p class="text-center mt-3">
                <Link
                    class="text-link"
                    :href="LogoutController()"
                    method="post"
                    as="button"
                >
                Logout
                </Link>
            </p>
        </div>
    </div>
</template>

<script lang="ts">
    import Layout from '@js/Layouts/Guest.vue';

    export default {
        layout: Layout,
    }
</script>

<script setup lang="ts">
    import { ref } from "vue";
    import { router } from "@inertiajs/vue3";

    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";
    import { update } from "@js/actions/App/Http/Controllers/EmailVerificationController";

    const title = ref<string>("Verify Your Email");

    const resend = () => {
        router.post(update().url, {
            preserveScroll: true,
        });
    };
</script>
