<script lang="ts" setup>
    import "vue-sonner/style.css";

    import type { ToasterProps } from "vue-sonner";
    import {
        CircleCheckIcon,
        InfoIcon,
        Loader2Icon,
        OctagonXIcon,
        TriangleAlertIcon,
        XIcon,
    } from "lucide-vue-next";
    import { computed } from "vue";
    import { Toaster as Sonner } from "vue-sonner";
    import { cn } from "@/lib/utils";

    const props = defineProps<ToasterProps>();

    const toastOptions = computed(() => ({
        ...props.toastOptions,
        unstyled: props.toastOptions?.unstyled ?? true,
        classes: {
            toast: cn(
                "group pointer-events-auto relative flex w-full items-center gap-3 overflow-hidden rounded-lg border p-4 pr-10 shadow-lg",
                props.toastOptions?.classes?.toast,
            ),
            title: cn(
                "text-sm font-medium leading-none tracking-tight",
                props.toastOptions?.classes?.title,
            ),
            description: cn(
                "text-sm text-current/80",
                props.toastOptions?.classes?.description,
            ),
            icon: cn(
                "shrink-0 text-current",
                props.toastOptions?.classes?.icon,
            ),
            closeButton: cn(
                "absolute top-3 right-3 rounded-md border border-current/15 p-1 text-current transition-colors hover:bg-black/5 hover:text-current dark:hover:bg-white/10",
                props.toastOptions?.classes?.closeButton,
            ),
            actionButton: cn(
                "inline-flex h-8 shrink-0 items-center justify-center rounded-md bg-foreground px-3 text-xs font-medium text-background transition-colors hover:opacity-90",
                props.toastOptions?.classes?.actionButton,
            ),
            cancelButton: cn(
                "inline-flex h-8 shrink-0 items-center justify-center rounded-md border border-current/15 px-3 text-xs font-medium transition-colors hover:bg-black/5 dark:hover:bg-white/10",
                props.toastOptions?.classes?.cancelButton,
            ),
            success: cn(
                "bg-emerald-50 text-emerald-950 border-emerald-200 dark:bg-emerald-950 dark:text-emerald-200 dark:border-emerald-800",
                props.toastOptions?.classes?.success,
            ),
            error: cn(
                "bg-red-50 text-red-950 border-red-200 dark:bg-red-950 dark:text-red-200 dark:border-red-800",
                props.toastOptions?.classes?.error,
            ),
            warning: cn(
                "bg-amber-50 text-amber-950 border-amber-200 dark:bg-amber-950 dark:text-amber-200 dark:border-amber-800",
                props.toastOptions?.classes?.warning,
            ),
            info: cn(
                "bg-sky-50 text-sky-950 border-sky-200 dark:bg-sky-950 dark:text-sky-200 dark:border-sky-800",
                props.toastOptions?.classes?.info,
            ),
            ...props.toastOptions?.classes,
        },
    }));
</script>

<template>
    <Sonner
        v-bind="props"
        :class="cn('toaster group', props.class)"
        :toast-options="toastOptions"
        :style="{
            '--border-radius': 'var(--radius)',
        }"
    >
        <template #success-icon>
            <CircleCheckIcon class="size-4" />
        </template>
        <template #info-icon>
            <InfoIcon class="size-4" />
        </template>
        <template #warning-icon>
            <TriangleAlertIcon class="size-4" />
        </template>
        <template #error-icon>
            <OctagonXIcon class="size-4" />
        </template>
        <template #loading-icon>
            <div>
                <Loader2Icon class="size-4 animate-spin" />
            </div>
        </template>
        <template #close-icon>
            <XIcon class="size-4" />
        </template>
    </Sonner>
</template>
