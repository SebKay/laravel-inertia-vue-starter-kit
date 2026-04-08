<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
            <Form
                :action="store()"
                :options="{ preserveScroll: 'errors' }"
                #default="{ errors, processing }"
            >
                <div class="form-row">
                    <div class="form-col">
                        <label class="label" for="email">Email</label>
                        <input
                            id="email"
                            class="input"
                            name="email"
                            type="email"
                            required
                            v-model="remembered.email"
                        />
                        <FieldError :message="errors.email" />
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="processing"
                        >
                            Email Reset Link
                        </button>
                    </div>
                </div>
            </Form>

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
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
    import Layout from "@js/layouts/Guest.vue";

    import FieldError from "@js/components/FieldError.vue";

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    defineOptions({
        layout: Layout,
    });

    const title = "Forgot Password";

    setLayoutProps({
        heading: title,
    });

    const remembered = useRemember(
        {
            email: "",
        },
        "ForgotPasswordForm",
    );
</script>
