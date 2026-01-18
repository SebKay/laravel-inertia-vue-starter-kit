<template>

    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <PageTitle
            class="mb-4 xl:mb-8"
            :text="title"
        />

        <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
            <Form
                v-bind="store.form()"
                #default="{ errors, processing }"
            >
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
                            name="email"
                            required
                            :value="email"
                        />
                        <FieldError :message="errors.email" />
                    </div>

                    <div class="form-col">
                        <label
                            class="label flex justify-between"
                            for="password"
                        >
                            Password
                            <Link
                                class="text-link"
                                :href="forgotPassword()"
                                text="Forgot password?"
                            />
                        </label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            name="password"
                            required
                            :value="password"
                        />
                        <FieldError :message="errors.password" />
                    </div>

                    <div class="form-col">
                        <label class="toggle">
                            <input
                                class="sr-only peer"
                                type="checkbox"
                                name="remember"
                                :checked="remember"
                            />
                            <div>
                            </div>
                            <span>
                                Remember me
                            </span>
                        </label>
                    </div>

                    <input
                        type="hidden"
                        name="redirect"
                        :value="redirect"
                    />

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="processing"
                        >
                            Log In
                        </button>
                    </div>
                </div>
            </Form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center mt-3">
                    Don't have an account?
                    <Link
                        class="text-link"
                        :href="register()"
                        text="Register"
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
    import { Form } from "@inertiajs/vue3";

    import type { PageProps } from "@js/types/inertia";

    import FieldError from "@js/Components/FieldError.vue";

    import { show as forgotPassword } from "@js/actions/App/Http/Controllers/ResetPasswordController";
    import { show as register } from "@js/actions/App/Http/Controllers/RegisterController";
    import { store } from "@js/actions/App/Http/Controllers/LoginController";

    const props = defineProps<PageProps<{
        email?: string;
        password?: string;
        remember?: boolean;
        redirect?: string;
    }>>();

    const title = "Log In";
    const email = props.email ?? "";
    const password = props.password ?? "";
    const remember = props.remember ?? false;
    const redirect = props.redirect ?? "";
</script>
