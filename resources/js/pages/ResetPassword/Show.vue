<template>
    <Head :title="title" />

    <div class="mx-auto max-w-xl">
        <Card>
            <CardContent>
                <Form
                    :action="store()"
                    :options="{ preserveScroll: 'errors' }"
                    #default="{ errors, processing }"
                >
                    <div class="grid gap-5">
                        <div class="grid gap-3">
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

                        <Button class="w-full" :disabled="processing">
                            Email reset link
                        </Button>
                    </div>
                </Form>
            </CardContent>

            <CardFooter class="flex justify-center">
                <p class="text-sm text-muted-foreground">
                    Remembered your password?
                    <Link
                        class="underline underline-offset-4 hover:opacity-80"
                        :href="login()"
                        prefetch
                    >
                        Log in
                    </Link>
                </p>
            </CardFooter>
        </Card>
    </div>
</template>

<script setup lang="ts">
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";
    import Layout from "@js/layouts/Guest.vue";

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

    import { show as login } from "@js/actions/App/Http/Controllers/LoginController";
    import { store } from "@js/actions/App/Http/Controllers/ResetPasswordController";

    defineOptions({
        layout: Layout,
    });

    const title = "Forgot Password";

    setLayoutProps({
        heading: title,
    });

    const remembered = useRemember(
        {
            email: "",
        },
        "ForgotPasswordForm",
    );
</script>
