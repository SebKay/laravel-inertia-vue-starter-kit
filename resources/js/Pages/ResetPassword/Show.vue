<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
            <form @submit.prevent="submit">
                <div class="form-row">
                    <div class="form-col">
                        <label class="label" for="email"> Email </label>
                        <input
                            id="email"
                            class="input"
                            type="email"
                            required
                            v-model="form.email"
                        />
                        <FieldError :message="form.errors.email" />
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="form.processing"
                        >
                            Email Reset Link
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center">
                    Remembered your password?
                    <Link class="text-link" :href="login()" prefetch
                        >Login</Link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps, useForm } from "@inertiajs/vue3";
    import Layout from "@js/Layouts/Guest.vue";

    import FieldError from "@js/Components/FieldError.vue";

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    defineOptions({
        layout: Layout,
    });

    const title = "Forgot Password";

    setLayoutProps({
        heading: title,
    });

    const form = useForm("ForgotPasswordForm", {
        email: "",
    });

    const submit = () => {
        form.submit(store(), {
            preserveScroll: "errors",
        });
    };
</script>
