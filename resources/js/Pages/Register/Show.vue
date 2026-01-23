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
                            for="name"
                        >
                            Name
                        </label>
                        <input
                            id="name"
                            class="input"
                            type="text"
                            name="name"
                            required
                            :value="name"
                        />
                        <FieldError :message="errors.name" />
                    </div>

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
                        <button
                            class="button button-full"
                            :disabled="processing"
                        >
                            Register
                        </button>
                    </div>
                </div>
            </Form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center">
                    Already have an account?
                    <Link
                        class="text-link"
                        :href="login()"
                        text="Log In"
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

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/RegisterController";

    const props = defineProps<PageProps<{
        name?: string;
        email?: string;
    }>>();

    const title = "Register";
    const name = props.name ?? "";
    const email = props.email ?? "";
</script>
