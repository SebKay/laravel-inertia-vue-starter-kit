<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl bg-white p-6 xl:p-10">
            <form @submit.prevent="submit">
                <div class="form-row">
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
                        <label class="label" for="password-confirmation">
                            Confirm Password
                        </label>
                        <input
                            id="password-confirmation"
                            class="input"
                            type="password"
                            required
                            v-model="form.password_confirmation"
                        />
                        <FieldError
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="form.processing"
                        >
                            Reset Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps, useForm } from "@inertiajs/vue3";
    import Layout from "@js/Layouts/Guest.vue";

    import type { PageProps } from "@js/types/inertia";

    import FieldError from "@js/Components/FieldError.vue";

    import { update } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    defineOptions({
        layout: Layout,
    });

    const props = defineProps<
        PageProps<{
            email?: string;
            token?: string;
        }>
    >();

    const title = "Reset Password";

    setLayoutProps({
        heading: title,
        subheading: "Choose a new password for your account.",
    });

    const form = useForm("ResetPasswordForm", {
        email: props.email ?? "",
        token: props.token ?? "",
        password: "",
        password_confirmation: "",
    }).dontRemember("password", "password_confirmation", "token");

    const submit = () => {
        form.submit(update(), {
            preserveScroll: "errors",
            onFinish: () => form.reset("password", "password_confirmation"),
        });
    };
</script>
