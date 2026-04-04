<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
            <Form
                :action="confirmPassword()"
                :on-finish="handleFinish"
                :options="{ preserveScroll: 'errors' }"
                #default="{ errors, processing }"
            >
                <div class="form-row">
                    <div class="form-col">
                        <label class="label" for="password">Password</label>
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
                        <button
                            class="button button-full"
                            :disabled="processing"
                        >
                            Confirm Password
                        </button>
                    </div>
                </div>
            </Form>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from "vue";
    import { Form, Head, setLayoutProps } from "@inertiajs/vue3";
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

    const password = ref("");

    const handleFinish = () => {
        password.value = "";
    };
</script>
