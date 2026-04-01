<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
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
                        <button
                            class="button button-full"
                            :disabled="form.processing"
                        >
                            Confirm Password
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

    import FieldError from "@js/Components/FieldError.vue";

    import { store as confirmPassword } from "@js/actions/App/Http/Controllers/ConfirmPasswordController";

    defineOptions({
        layout: Layout,
    });

    const title = "Confirm Password";

    setLayoutProps({
        heading: title,
    });

    const form = useForm("PasswordForm", {
        password: "",
    }).dontRemember("password");

    const submit = () => {
        form.submit(confirmPassword(), {
            preserveScroll: "errors",
            onFinish: () => form.reset("password"),
        });
    };
</script>
