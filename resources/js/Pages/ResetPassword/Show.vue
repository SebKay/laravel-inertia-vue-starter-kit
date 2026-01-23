<template>

    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <PageTitle
            class="mb-4 xl:mb-8"
            :text="title"
        />

        <div class="bg-white rounded-2xl xl:p-10 p-6">
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
    import { Form } from "@inertiajs/vue3";

    import FieldError from "@js/Components/FieldError.vue";

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    const title = "Forgot Password";
</script>
