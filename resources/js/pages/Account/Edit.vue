<template>
    <Head :title="title" />

    <div class="max-w-xl">
        <Card>
            <CardHeader>
                <CardTitle>Account</CardTitle>
                <CardDescription>Update your profile details.</CardDescription>
            </CardHeader>
            <CardContent>
        <Form
            :action="update()"
            :options="{ preserveScroll: true, preserveState: true }"
            :reset-on-success="['password']"
            set-defaults-on-success
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
                    <Label for="password">New password</Label>
                    <Input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                    />
                    <p class="text-sm text-muted-foreground">
                        Leave blank to keep your current password.
                    </p>
                    <p
                        v-if="errors.password"
                        class="text-sm text-destructive"
                        v-text="errors.password"
                    />
                </div>

                <Button :disabled="processing">Update</Button>
            </div>
        </Form>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
    import { Form, Head, setLayoutProps, useRemember } from "@inertiajs/vue3";

    import type { PageProps, UserDocument } from "@js/types/inertia";

    import { Button } from "@/components/ui/button";
    import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";

    import { update } from "@js/actions/App/Http/Controllers/AccountController";

    const title = "Account";
    const props = defineProps<
        PageProps<{
            user: UserDocument;
        }>
    >();

    setLayoutProps({
        heading: title,
    });

    const user = props.user ?? props.auth.user;

    if (!user) {
        throw new Error(
            "Authenticated user data is required for the account page.",
        );
    }

    const remembered = useRemember(
        {
            name:
                (user.data.attributes.name as string | null | undefined) ?? "",
            email:
                (user.data.attributes.email as string | null | undefined) ?? "",
        },
        `AccountEdit:${user.data.id}`,
    );
</script>
