<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
            <Form
                :action="store()"
                :on-finish="handleFinish"
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
                        <label
                            class="label flex justify-between"
                            for="password"
                        >
                            Password
                            <Link
                                class="text-link"
                                :href="forgotPassword()"
                                prefetch
                            >
                                Forgot password?
                            </Link>
                        </label>
                        <input
                            id="password"
                            class="input"
                            name="password"
                            type="password"
                            required
                            v-model="password"
                        />
                        <FieldError :message="errors.password" />
                    </div>

                    <div class="form-col">
                        <label class="toggle">
                            <input
                                class="peer sr-only"
                                name="remember"
                                type="checkbox"
                                v-model="remembered.remember"
                            />
                            <div></div>
                            <span>Remember me</span>
                        </label>
                    </div>

                    <input
                        v-model="remembered.redirect"
                        name="redirect"
                        type="hidden"
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
                <p class="mt-3 text-center">
                    Don't have an account?
                    <Link class="text-link" :href="register()" prefetch>
                        Register
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from "vue";
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
    import Layout from "@js/layouts/Guest.vue";

    import type { PageProps } from "@js/types/inertia";

    import FieldError from "@js/components/FieldError.vue";

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
    });

    const remembered = useRemember(
        {
            email: props.email ?? "",
            remember: props.remember ?? false,
            redirect: props.redirect ?? "",
        },
        "LoginForm",
    );
    const password = ref(props.password ?? "");

    const handleFinish = () => {
        password.value = "";
    };
</script>
