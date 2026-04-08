<template>
    <Head :title="title" />

    <div class="max-w-3xl">
        <Card>
            <CardHeader>
                <CardTitle>Elements</CardTitle>
                <CardDescription>
                    Placeholder page for UI components and content styles.
                </CardDescription>
            </CardHeader>
            <CardContent class="space-y-6">
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
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
    import { setLayoutProps } from "@inertiajs/vue3";

    import { Button } from "@/components/ui/button";
    import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";
    import { Separator } from "@/components/ui/separator";

    const title = "Elements";

    setLayoutProps({
        heading: title,
    });
</script>
