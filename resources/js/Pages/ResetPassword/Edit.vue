<template>

    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <PageTitle
            class="mb-4 xl:mb-8"
            :text="title"
        />

        <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
            <Form
                v-bind="update.form()"
                #default="{ errors, processing }"
            >
                <div class="form-row">
                    <input
                        type="hidden"
                        name="email"
                        :value="email"
                    />
                    <input
                        type="hidden"
                        name="token"
                        :value="token"
                    />

                    <div class="form-col">
                        <label
                            class="label"
                            for="password"
                        >
                            Password
                        </label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            name="password"
                            required
                        />
                        <FieldError :message="errors.password" />
                    </div>

                    <div class="form-col">
                        <label
                            class="label"
                            for="password-confirmation"
                        >
                            Confirm Password
                        </label>
                        <input
                            id="password-confirmation"
                            class="input"
                            type="password"
                            name="password_confirmation"
                            required
                        />
                        <FieldError :message="errors.password_confirmation" />
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="processing"
                        >
                            Reset Password
                        </button>
                    </div>
                </div>
            </Form>
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

    import { update } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    const props = defineProps<PageProps<{
        email?: string;
        token?: string;
    }>>();

    const title = "Reset Password";
    const email = props.email ?? "";
    const token = props.token ?? "";
</script>
