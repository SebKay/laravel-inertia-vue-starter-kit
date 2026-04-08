<template>
    <Head :title="title" />

    <div class="mx-auto max-w-xl">
        <Card>
            <CardContent>
                <Form
                    :action="update()"
                    :on-finish="handleFinish"
                    :options="{ preserveScroll: 'errors' }"
                    #default="{ errors, processing }"
                >
                    <div class="grid gap-5">
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

                        <div class="grid gap-3">
                            <Label for="password">Password</Label>
                            <Input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                required
                                v-model="password"
                            />
                            <p
                                v-if="errors.password"
                                class="text-sm text-destructive"
                                v-text="errors.password"
                            />
                        </div>

                        <div class="grid gap-3">
                            <Label for="password-confirmation"
                                >Confirm password</Label
                            >
                            <Input
                                id="password-confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                required
                                v-model="passwordConfirmation"
                            />
                            <p
                                v-if="errors.password_confirmation"
                                class="text-sm text-destructive"
                                v-text="errors.password_confirmation"
                            />
                        </div>

                        <Button class="w-full" :disabled="processing">
                            Reset Password
                        </Button>
                    </div>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
    import { ref } from "vue";

    import { Button } from "@/components/ui/button";
    import { Card, CardContent } from "@/components/ui/card";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";

    import { update } from "@js/actions/App/Http/Controllers/ResetPasswordController";
    import Layout from "@js/layouts/Guest.vue";
    import type { PageProps } from "@js/types/inertia";

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
