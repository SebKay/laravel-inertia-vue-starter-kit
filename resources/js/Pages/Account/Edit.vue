<template>

    <Head :title="title" />

    <h1
        v-text="title"
        class="xl:text-4xl text-3xl font-medium text-neutral-900 xl:mb-8 mb-4"
    ></h1>

    <div class="bg-white rounded-2xl xl:p-10 p-6">
        <Form
            v-bind="update.form()"
            :data="{ preserveScroll: true, preserveState: 'errors' }"
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
                        :value="user.name"
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
                        :value="user.email"
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
                    />
                    <p class="field-hint">Leave blank to keep current password</p>
                    <FieldError :message="errors.password" />
                </div>

                <div class="form-col">
                    <button
                        class="button"
                        :disabled="processing"
                    >
                        Update
                    </button>
                </div>
            </div>
        </Form>
    </div>
</template>

<script setup lang="ts">
    import { Form } from "@inertiajs/vue3";

    import type { PageProps, User } from "@js/types/inertia";

    import FieldError from "@js/Components/FieldError.vue";

    import { update } from "@js/actions/App/Http/Controllers/AccountController";

    const title = "Update Account";
    const props = defineProps<PageProps<{
        user: User;
    }>>();

    const user = props.user;
</script>
