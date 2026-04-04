<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
            <Form
                :action="update()"
                :on-finish="handleFinish"
                :options="{ preserveScroll: 'errors' }"
                #default="{ errors, processing }"
            >
                <div class="form-row">
                    <input
                        name="email"
                        type="hidden"
                        :value="remembered.email"
                    />
                    <input
                        name="token"
                        type="hidden"
                        :value="props.token ?? ''"
                    />

                    <div class="form-col">
                        <label class="label" for="password"> Password </label>
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
                        <label class="label" for="password-confirmation">
                            Confirm Password
                        </label>
                        <input
                            id="password-confirmation"
                            class="input"
                            name="password_confirmation"
                            type="password"
                            required
                            v-model="passwordConfirmation"
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

<script setup lang="ts">
    import { ref } from "vue";
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
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
    });

    const remembered = useRemember(
        {
            email: props.email ?? "",
        },
        "ResetPasswordForm",
    );
    const password = ref("");
    const passwordConfirmation = ref("");

    const handleFinish = () => {
        password.value = "";
        passwordConfirmation.value = "";
    };
</script>
