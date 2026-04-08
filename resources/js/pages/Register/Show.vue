<template>
    <Head :title="title" />

    <div class="mx-auto max-w-md">
        <Card>
            <CardHeader>
                <CardTitle>Create an account</CardTitle>
                <CardDescription>
                    Enter your details below to get started.
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
                            <Label for="name">Name</Label>
                            <Input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                required
                                v-model="remembered.name"
                            />
                            <p
                                v-if="errors.name"
                                class="text-sm text-destructive"
                                v-text="errors.name"
                            />
                        </div>

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

                        <Button class="w-full" :disabled="processing">
                            Register
                        </Button>
                    </div>
                </Form>
            </CardContent>

            <CardFooter class="flex justify-center">
                <p class="text-sm text-muted-foreground">
                    Already have an account?
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
