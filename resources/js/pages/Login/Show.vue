<template>
    <Head :title="title" />

    <div class="mx-auto max-w-md">
        <Card>
            <CardHeader>
                <CardTitle>Log in</CardTitle>
                <CardDescription>
                    Enter your email and password to continue.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Form
                    :action="store()"
                    :on-finish="handleFinish"
                    :options="{ preserveScroll: 'errors' }"
                    #default="{ errors, processing }"
                >
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                v-model="remembered.email"
                            />
                            <p
                                v-if="errors.email"
                                class="text-sm text-destructive"
                                v-text="errors.email"
                            />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="password">Password</Label>
                                <Link
                                    class="text-sm underline underline-offset-4 hover:opacity-80"
                                    :href="forgotPassword()"
                                    prefetch
                                >
                                    Forgot password?
                                </Link>
                            </div>
                            <Input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                v-model="password"
                            />
                            <p
                                v-if="errors.password"
                                class="text-sm text-destructive"
                                v-text="errors.password"
                            />
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                id="remember"
                                class="h-4 w-4 rounded border-input text-primary"
                                name="remember"
                                type="checkbox"
                                v-model="remembered.remember"
                            />
                            <Label for="remember" class="text-sm font-normal">
                                Remember me
                            </Label>
                        </div>

                        <input
                            v-model="remembered.redirect"
                            name="redirect"
                            type="hidden"
                        />

                        <Button class="w-full" :disabled="processing">
                            Log in
                        </Button>
                    </div>
                </Form>
            </CardContent>

            <CardFooter class="flex justify-center">
                <p class="text-sm text-muted-foreground">
                    Don't have an account?
                    <Link
                        class="underline underline-offset-4 hover:opacity-80"
                        :href="register()"
                        prefetch
                    >
                        Register
                    </Link>
                </p>
            </CardFooter>
        </Card>
    </div>
</template>

<script setup lang="ts">
    import { ref } from "vue";
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
    import Layout from "@js/layouts/Guest.vue";

    import type { PageProps } from "@js/types/inertia";

    import { Button } from "@/components/ui/button";
    import {
        Card,
        CardContent,
        CardDescription,
        CardFooter,
        CardHeader,
        CardTitle,
    } from "@/components/ui/card";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";

    import { show as forgotPassword } from "@js/actions/App/Http/Controllers/ResetPasswordController";
    import { show as register } from "@js/actions/App/Http/Controllers/RegisterController";
    import { store } from "@js/actions/App/Http/Controllers/LoginController";

    defineOptions({
        layout: Layout,
    });

    const props = defineProps<
        PageProps<{
            email?: string;
            password?: string;
            remember?: boolean;
            redirect?: string;
        }>
    >();

    const title = "Log In";

    setLayoutProps({
        heading: title,
    });

    const remembered = useRemember(
        {
            email: props.email ?? "",
            remember: props.remember ?? false,
            redirect: props.redirect ?? "",
        },
        "LoginForm",
    );
    const password = ref(props.password ?? "");

    const handleFinish = () => {
        password.value = "";
    };
</script>
