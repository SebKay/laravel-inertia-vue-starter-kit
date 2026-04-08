<template>
    <Head :title="title" />

    <div class="mx-auto max-w-md space-y-4">
        <Card>
            <CardHeader>
                <CardTitle>Verify your email</CardTitle>
                <CardDescription>
                    We’ve sent a verification link to your email address.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Button
                    class="w-full"
                    :disabled="resendRequest.processing"
                    @click="resend"
                >
                    {{
                        resendRequest.processing
                            ? "Sending..."
                            : "Resend verification email"
                    }}
                </Button>

                <p
                    v-if="statusMessage"
                    class="mt-3 text-sm text-muted-foreground"
                    v-text="statusMessage"
                />
            </CardContent>
        </Card>

        <div class="text-center">
            <Link
                class="text-sm underline underline-offset-4 hover:opacity-80"
                :href="LogoutController()"
                method="post"
                as="button"
            >
                Logout
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref } from "vue";
    import { Head, setLayoutProps, useHttp } from "@inertiajs/vue3";
    import Layout from "@js/layouts/Guest.vue";

    import { Button } from "@/components/ui/button";
    import {
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
    } from "@/components/ui/card";

    import LogoutController from "@js/actions/App/Http/Controllers/LogoutController";
    import { update } from "@js/actions/App/Http/Controllers/EmailVerificationController";

    defineOptions({
        layout: Layout,
    });

    const title = "Verify Your Email";

    setLayoutProps({
        heading: title,
    });

    const resendRequest = useHttp("EmailVerificationResend", {});
    const statusMessage = ref("");

    const resend = () => {
        statusMessage.value = "";

        resendRequest.post(update().url, {
            onSuccess: () => {
                statusMessage.value = "Verification email sent.";
            },
            onError: (errors) => {
                statusMessage.value =
                    errors.message ??
                    "Unable to resend verification email right now.";
            },
        });
    };
</script>
