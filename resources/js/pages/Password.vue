<template>
    <Head :title="title" />

    <div class="p-4 md:p-6">
        <div class="max-w-2xl">
            <div class="space-y-6">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold">Password Test</h2>
                    <p class="text-sm text-muted-foreground">
                        This internal page exists to exercise the password
                        confirmation middleware with a real Inertia route.
                    </p>
                </div>

                <div class="rounded-lg border bg-card p-6 text-card-foreground">
                    <div class="space-y-3">
                        <p class="text-sm leading-6">
                            If you can see this page, your current session has
                            passed Laravel's recent password confirmation
                            check.
                        </p>
                        <p class="text-sm leading-6 text-muted-foreground">
                            Use it when validating redirects to the password
                            confirmation form or when checking that the
                            middleware remains wired correctly after auth
                            changes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { Head, setLayoutProps } from "@inertiajs/vue3";

    const title = "Password Test";

    setLayoutProps({
        heading: title,
    });
</script>
