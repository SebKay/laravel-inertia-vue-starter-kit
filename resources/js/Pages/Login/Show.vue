<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl bg-white p-6 xl:p-10">
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
                        <label
                            class="label flex justify-between"
                            for="password"
                        >
                            Password
                            <Link
                                class="text-link"
                                :href="forgotPassword()"
                                prefetch
                                >Forgot password?</Link
                            >
                        </label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            required
                            v-model="form.password"
                        />
                        <FieldError :message="form.errors.password" />
                    </div>

                    <div class="form-col">
                        <label class="toggle">
                            <input
                                class="peer sr-only"
                                type="checkbox"
                                v-model="form.remember"
                            />
                            <div></div>
                            <span> Remember me </span>
                        </label>
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="form.processing"
                        >
                            Log In
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 xl:mt-10">
                <p class="mt-3 text-center">
                    Don't have an account?
                    <Link class="text-link" :href="register()" prefetch
                        >Register</Link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head, setLayoutProps, useForm } from "@inertiajs/vue3";
import Layout from "@js/Layouts/Guest.vue";

import type { PageProps } from "@js/types/inertia";

import FieldError from "@js/Components/FieldError.vue";

import { show as forgotPassword } from "@js/actions/App/Http/Controllers/ResetPasswordController";
import { show as register } from "@js/actions/App/Http/Controllers/RegisterController";
import { store } from "@js/actions/App/Http/Controllers/LoginController";

defineOptions({
    layout: Layout,
});

const props = defineProps<
    PageProps<{
        email?: string;
        password?: string;
        remember?: boolean;
        redirect?: string;
    }>
>();

const title = "Log In";

setLayoutProps({
    heading: title,
    subheading: "Sign in with your account to continue.",
});

const form = useForm("LoginForm", {
    email: props.email ?? "",
    password: props.password ?? "",
    remember: props.remember ?? false,
    redirect: props.redirect ?? "",
}).dontRemember("password");

const submit = () => {
    form.submit(store(), {
        preserveScroll: "errors",
        onFinish: () => form.reset("password"),
    });
};
</script>
