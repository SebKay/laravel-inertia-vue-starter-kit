<script setup lang="ts">
    import type { CheckboxRootEmits, CheckboxRootProps } from "reka-ui";
    import type { HTMLAttributes } from "vue";
    import { reactiveOmit } from "@vueuse/core";
    import { CheckIcon } from "lucide-vue-next";
    import {
        CheckboxIndicator,
        CheckboxRoot,
        useForwardPropsEmits,
    } from "reka-ui";
    import { cn } from "@/lib/utils";

    const props = defineProps<
        CheckboxRootProps & { class?: HTMLAttributes["class"] }
    >();
    const emits = defineEmits<CheckboxRootEmits>();

    const delegatedProps = reactiveOmit(props, "class");
    const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <CheckboxRoot
        v-bind="forwarded"
        data-slot="checkbox"
        :class="
            cn(
                'peer size-4 shrink-0 rounded-[4px] border border-input shadow-xs transition-shadow outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:border-primary data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground dark:bg-input/30',
                props.class,
            )
        "
    >
        <CheckboxIndicator
            data-slot="checkbox-indicator"
            class="flex items-center justify-center text-current transition-none"
        >
            <CheckIcon class="size-3.5" />
        </CheckboxIndicator>
    </CheckboxRoot>
</template>
