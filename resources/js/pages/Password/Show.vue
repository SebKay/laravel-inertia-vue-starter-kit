<template>
    <Head :title="title" />

    <div class="mx-auto max-w-xl">
        <Card>
            <CardContent>
                <Form
                    :action="confirmPassword()"
                    :on-finish="handleFinish"
                    :options="{ preserveScroll: 'errors' }"
                    #default="{ errors, processing }"
                >
                    <div class="grid gap-5">
                        <div class="grid gap-3">
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
    import { Form, Head, setLayoutProps } from "@inertiajs/vue3";
    import { ref } from "vue";

    import { Button } from "@/components/ui/button";
    import { Card, CardContent } from "@/components/ui/card";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";

    import { store as confirmPassword } from "@js/actions/App/Http/Controllers/ConfirmPasswordController";
    import Layout from "@js/layouts/Guest.vue";

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
