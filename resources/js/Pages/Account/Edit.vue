<template>
    <Head :title="title" />

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
                        v-model="form.password"
                    />
                    <p class="field-hint">
                        Leave blank to keep current password
                    </p>
                    <FieldError :message="form.errors.password" />
                </div>

                <div class="form-col">
                    <button class="button" :disabled="form.processing">
                        Update
                    </button>

                    <p v-if="form.recentlySuccessful" class="field-hint mt-3">
                        Account details saved.
                    </p>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps, useForm } from "@inertiajs/vue3";

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

    const form = useForm(`AccountEdit:${user.data.id}`, {
        name: (user.data.attributes.name as string | null | undefined) ?? "",
        email: (user.data.attributes.email as string | null | undefined) ?? "",
        password: "",
    }).dontRemember("password");

    const submit = () => {
        form.submit(update(), {
            preserveScroll: true,
            preserveState: "errors",
            onSuccess: () => {
                form.defaults({
                    name: form.name,
                    email: form.email,
                    password: "",
                });
            },
            onFinish: () => form.reset("password"),
        });
    };
</script>
