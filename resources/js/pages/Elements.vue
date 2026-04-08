<template>
    <Head :title="title" />

    <div class="p-4 md:p-6">
        <div class="max-w-3xl">
            <div class="space-y-6">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold">Elements</h2>
                    <p class="text-sm text-muted-foreground">
                        Placeholder page for UI components and content styles.
                    </p>
                </div>
                <div class="space-y-6">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="text">Text</Label>
                            <Input id="text" type="text" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" />
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <Button>Primary</Button>
                        <Button variant="secondary">Secondary</Button>
                        <Button variant="outline">Outline</Button>
                        <Button variant="ghost">Ghost</Button>
                    </div>

                    <Separator />

                    <div class="space-y-2">
                        <h2 class="text-lg font-semibold">Content</h2>
                        <p class="text-sm text-muted-foreground">
                            This page will be removed once the app has real pages.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { setLayoutProps } from "@inertiajs/vue3";

    import { Button } from "@/components/ui/button";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";
    import { Separator } from "@/components/ui/separator";

    const title = "Elements";

    setLayoutProps({
        heading: title,
    });
</script>
