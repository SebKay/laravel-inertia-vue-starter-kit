<template>
    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <div class="rounded-xl bg-white p-6 xl:p-10">
            <Form
                :action="store()"
                :on-finish="handleFinish"
                :options="{ preserveScroll: 'errors' }"
                #default="{ errors, processing }"
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
                            Register
                        </button>
                    </div>
                </div>
            </Form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center">
                    Already have an account?
                    <Link class="text-link" :href="login()" prefetch
                        >Log In</Link
                    >
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from "vue";
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
    import Layout from "@js/Layouts/Guest.vue";

    import type { PageProps } from "@js/types/inertia";

    import FieldError from "@js/Components/FieldError.vue";

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/RegisterController";

    defineOptions({
        layout: Layout,
    });

    const props = defineProps<
        PageProps<{
            name?: string;
            email?: string;
        }>
    >();

    const title = "Register";

    setLayoutProps({
        heading: title,
    });

    const remembered = useRemember(
        {
            name: props.name ?? "",
            email: props.email ?? "",
        },
        "RegisterForm",
    );
    const password = ref("");

    const handleFinish = () => {
        password.value = "";
    };
</script>
