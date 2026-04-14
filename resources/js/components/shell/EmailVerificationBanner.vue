<script setup lang="ts">
    import { useHttp, usePage } from "@inertiajs/vue3";
    import {
        Loader2 as Loader2Icon,
        TriangleAlert as TriangleAlertIcon,
    } from "lucide-vue-next";
    import { computed } from "vue";

    import { Button } from "@/components/ui/button";
    import { toast } from "@/components/ui/sonner";

    import { update as resendVerification } from "@js/actions/App/Http/Controllers/EmailVerificationController";
    import type { PageProps } from "@js/types/inertia";

    const TOAST_DURATION = 4000;
    type VerificationResponse = {
        message: string;
    };

    const page = usePage<PageProps>();
    const resendRequest = useHttp<Record<string, never>, VerificationResponse>(
        "EmailVerificationBannerResend",
        {},
    );

    const user = computed(() => page.props.auth.user);
    const emailAddress = computed(() => {
        if (!user.value) {
            return null;
        }

        return (
            (user.value.data.attributes.email as string | null | undefined) ??
            null
        );
    });

    const showBanner = computed(() => {
        if (!user.value) {
            return false;
        }

        return !user.value.data.attributes.emailVerified;
    });

    const resend = () => {
        resendRequest.post(resendVerification().url, {
            onSuccess: (response) => {
                toast.success(response.message, {
                    duration: TOAST_DURATION,
                });
            },
            onError: (errors) => {
                toast.error(
                    errors.message ??
                        "Unable to resend verification email right now.",
                    {
                        duration: TOAST_DURATION,
                    },
                );
            },
        });
    };
</script>

<template>
    <section
        v-if="showBanner"
        data-email-verification-banner
        role="alert"
        class="bg-amber-50 text-amber-950 md:rounded-xl dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200"
    >
        <div
            class="flex flex-col gap-4 px-4 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-6"
        >
            <div class="flex items-center gap-3">
                <div
                    class="mt-0.5 self-start rounded-full bg-amber-100 p-2 text-amber-900 dark:bg-amber-900/60 dark:text-amber-100"
                >
                    <TriangleAlertIcon class="size-4" />
                </div>

                <div>
                    <p class="text-sm font-medium">
                        Verify your email address to keep your account fully
                        active.
                    </p>

                    <p class="text-sm/6 text-current/80">
                        <template v-if="emailAddress">
                            We sent a verification link to
                            <strong>{{ emailAddress }}</strong
                            >.
                        </template>
                        <template v-else>
                            Check your inbox for your verification email.
                        </template>
                        If you need another link, resend it here.
                    </p>
                </div>
            </div>

            <Button
                variant="amber"
                size="sm"
                :disabled="resendRequest.processing"
                @click="resend"
            >
                <Loader2Icon
                    v-if="resendRequest.processing"
                    class="size-4 animate-spin"
                />
                <span>
                    {{
                        resendRequest.processing
                            ? "Sending..."
                            : "Resend verification email"
                    }}
                </span>
            </Button>
        </div>
    </section>
</template>
