<template>
    <Head :title="title" />

    <div class="mx-auto max-w-md">
        <Card>
            <CardHeader>
                <CardTitle>Confirm password</CardTitle>
                <CardDescription>
                    For your security, please confirm your password to continue.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Form
                    :action="confirmPassword()"
                    :on-finish="handleFinish"
                    :options="{ preserveScroll: 'errors' }"
                    #default="{ errors, processing }"
                >
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="password">Password</Label>
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

                        <Button class="w-full" :disabled="processing">
                            Confirm password
                        </Button>
                    </div>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
    import { ref } from "vue";
    import { Form, Head, setLayoutProps } from "@inertiajs/vue3";
    import Layout from "@js/layouts/Guest.vue";

    import { Button } from "@/components/ui/button";
    import {
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
    } from "@/components/ui/card";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";

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
