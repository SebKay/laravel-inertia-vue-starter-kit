<template>

    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <PageTitle
            class="mb-4 xl:mb-8"
            :text="title"
        />

        <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
            <form @submit.prevent="submitForm">
                <div class="form-row">
                    <div class="form-col">
                        <label
                            class="label"
                            for="email"
                        >
                            Email
                        </label>
                        <input
                            id="email"
                            class="input"
                            type="email"
                            required
                            v-model="forgotPasswordForm.email"
                        />
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="forgotPasswordForm.processing"
                        >
                            Email Reset Link
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center">
                    Remembered your password?
                    <Link
                        class="text-link"
                        :href="login()"
                        text="Login"
                    />
                </p>
            </div>
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
    import { useForm } from "@inertiajs/vue3";

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    const title = ref<string>("Forgot Password");
    const forgotPasswordForm = useForm({
        email: "",
    });

    const submitForm = () => {
        forgotPasswordForm.submit(store());
    };
</script>
