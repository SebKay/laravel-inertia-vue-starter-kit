<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl bg-white p-6 xl:p-10">
            <form @submit.prevent="submit">
                <div class="form-row">
                    <div class="form-col">
                        <label class="label" for="name"> Name </label>
                        <input
                            id="name"
                            class="input"
                            type="text"
                            required
                            v-model="form.name"
                        />
                        <FieldError :message="form.errors.name" />
                    </div>

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
                        <label class="label" for="password"> Password </label>
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
                        <button
                            class="button button-full"
                            :disabled="form.processing"
                        >
                            Register
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center">
                    Already have an account?
                    <Link class="text-link" :href="login()" prefetch
                        >Log In</Link
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

import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
import { store } from "@js/actions/App/Http/Controllers/RegisterController";

defineOptions({
    layout: Layout,
});

const props = defineProps<
    PageProps<{
        name?: string;
        email?: string;
    }>
>();

const title = "Register";

setLayoutProps({
    heading: title,
    subheading:
        "Create your account and you'll land straight in the dashboard.",
});

const form = useForm("RegisterForm", {
    name: props.name ?? "",
    email: props.email ?? "",
    password: "",
}).dontRemember("password");

const submit = () => {
    form.submit(store(), {
        preserveScroll: "errors",
        onFinish: () => form.reset("password"),
    });
};
</script>
