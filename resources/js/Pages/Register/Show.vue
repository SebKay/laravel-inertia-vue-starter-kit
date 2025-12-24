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
                            for="first-name"
                        >
                            First Name
                        </label>
                        <input
                            id="first-name"
                            class="input"
                            type="text"
                            required
                            v-model="registerForm.first_name"
                        />
                    </div>

                    <div class="form-col">
                        <label
                            class="label"
                            for="last-name"
                        >
                            Last Name
                        </label>
                        <input
                            id="last-name"
                            class="input"
                            type="text"
                            required
                            v-model="registerForm.last_name"
                        />
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
                            required
                            v-model="registerForm.email"
                        />
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
                            required
                            v-model="registerForm.password"
                        />
                    </div>

                    <div class="form-col">
                        <button
                            class="button button-full"
                            :disabled="registerForm.processing"
                        >
                            Register
                        </button>
                    </div>
                </div>
            </form>

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
    import { ref, type PropType } from "vue";
    import { useForm } from "@inertiajs/vue3";

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/RegisterController";

    const props = defineProps({
        first_name: String as PropType<string>,
        last_name: String as PropType<string>,
        email: String as PropType<string>,
        password: String as PropType<string>,
    });

    const title = ref<string>("Register");
    const registerForm = useForm({
        first_name: props.first_name ?? "",
        last_name: props.last_name ?? "",
        email: props.email ?? "",
        password: props.password ?? "",
    });

    const submitForm = () => {
        registerForm.submit(store());
    };
</script>
