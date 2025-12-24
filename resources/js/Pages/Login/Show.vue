<template>

    <Head :title="title" />

    <div class="mx-auto max-w-2xl">
        <PageTitle
            class="mb-4 xl:mb-8"
            :text="title"
        />

        <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
            <form @submit.prevent="submitForm">
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
                            required
                            v-model="loginForm.email"
                        />
                    </div>

                    <div class="form-col">
                        <label
                            class="label flex justify-between"
                            for="password"
                        >
                            Password
                            <Link
                                class="text-link"
                                :href="forgotPassword()"
                                text="Forgot password?"
                            />
                        </label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            required
                            v-model="loginForm.password"
                        />
                    </div>

                    <div class="form-col">
                        <label class="toggle">
                            <input
                                class="sr-only peer"
                                type="checkbox"
                                v-model="loginForm.remember"
                            />
                            <div class="peer">
                            </div>
                            <span>
                                Remember me
                            </span>
                        </label>
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="loginForm.processing"
                        >
                            Log In
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 xl:mt-10">
                <p class="text-center mt-3">
                    Don't have an account?
                    <Link
                        class="text-link"
                        :href="register()"
                        text="Register"
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
    import { ref, type PropType } from "vue";
    import { useForm } from "@inertiajs/vue3";

    import { show as forgotPassword } from "@js/actions/App/Http/Controllers/ResetPasswordController";
    import { show as register } from "@js/actions/App/Http/Controllers/RegisterController";
    import { store } from "@js/actions/App/Http/Controllers/LoginController";

    const props = defineProps({
        email: String as PropType<string>,
        password: String as PropType<string>,
        remember: Boolean as PropType<boolean>,
        redirect: String as PropType<string>,
    });

    const title = ref<string>("Log In");
    const loginForm = useForm({
        email: props.email ?? "",
        password: props.password ?? "",
        remember: props.remember ?? false,
        redirect: props.redirect ?? "",
    });

    const submitForm = () => {
        loginForm.submit(store());
    };
</script>
