<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="bg-white rounded-2xl xl:p-10 p-6">
            <div class="text-center">
                <p>
                    Please verify your email address by clicking on the link we just emailed to you.
                </p>
                <button
                    class="button mt-6"
                    :disabled="resendRequest.processing"
                    @click="resend"
                >
                    {{ resendRequest.processing ? 'Sending...' : 'Resend Verification Email' }}
                </button>

                <p v-if="statusMessage" class="field-hint mt-4" v-text="statusMessage"></p>
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

<script setup lang="ts">
    import { ref } from "vue";
    import { Head, setLayoutProps, useHttp } from "@inertiajs/vue3";
    import Layout from '@js/Layouts/Guest.vue';

    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";
    import { update } from "@js/actions/App/Http/Controllers/EmailVerificationController";

    defineOptions({
        layout: Layout,
    });

    const title = "Verify Your Email";

    setLayoutProps({
        heading: title,
        subheading: "Confirm your address to unlock the rest of the app.",
    });

    const resendRequest = useHttp('EmailVerificationResend', {});
    const statusMessage = ref('');

    const resend = () => {
        statusMessage.value = '';

        resendRequest.post(update().url, {
            onSuccess: () => {
                statusMessage.value = 'Verification email sent.';
            },
            onError: (errors) => {
                statusMessage.value = errors.message ?? 'Unable to resend verification email right now.';
            },
        });
    };
</script>
