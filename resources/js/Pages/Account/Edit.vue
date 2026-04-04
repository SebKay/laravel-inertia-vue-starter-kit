<template>
    <Head :title="title" />

    <div class="max-w-3xl">
        <Form
            :action="update()"
            :options="{ preserveScroll: true, preserveState: true }"
            :reset-on-success="['password']"
            set-defaults-on-success
            #default="{ errors, processing, recentlySuccessful }"
        >
            <div class="form-row">
                <div class="form-col">
                    <label class="label" for="name"> Name </label>
                    <input
                        id="name"
                        class="input"
                        name="name"
                        type="text"
                        required
                        v-model="remembered.name"
                    />
                    <FieldError :message="errors.name" />
                </div>

                <div class="form-col">
                    <label class="label" for="email"> Email </label>
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
                    <label class="label" for="password"> Password </label>
                    <input
                        id="password"
                        class="input"
                        name="password"
                        type="password"
                    />
                    <p class="field-hint">
                        Leave blank to keep current password
                    </p>
                    <FieldError :message="errors.password" />
                </div>

                <div class="form-col">
                    <button class="button" :disabled="processing">
                        Update
                    </button>

                    <p v-if="recentlySuccessful" class="field-hint mt-3">
                        Account details saved.
                    </p>
                </div>
            </div>
        </Form>
    </div>
</template>

<script setup lang="ts">
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";

    import type { PageProps, UserDocument } from "@js/types/inertia";

    import FieldError from "@js/Components/FieldError.vue";

    import { update } from "@js/actions/App/Http/Controllers/AccountController";

    const title = "Account";
    const props = defineProps<
        PageProps<{
            user: UserDocument;
        }>
    >();

    setLayoutProps({
        heading: title,
    });

    const user = props.user ?? props.auth.user;

    if (!user) {
        throw new Error(
            "Authenticated user data is required for the account page.",
        );
    }

    const remembered = useRemember(
        {
            name:
                (user.data.attributes.name as string | null | undefined) ?? "",
            email:
                (user.data.attributes.email as string | null | undefined) ?? "",
        },
        `AccountEdit:${user.data.id}`,
    );
</script>
