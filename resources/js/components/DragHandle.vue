<script setup lang="ts">
    import { IconGripVertical } from "@tabler/icons-vue";
    import { useSortableContext } from "dnd-kit-vue";
    import { Button } from "@/components/ui/button";

    const { handleRef, sortable } = useSortableContext();
</script>

<template>
    <Button
        :ref="handleRef"
        variant="ghost"
        size="icon"
        class="size-7 text-muted-foreground hover:bg-transparent"
    >
        <IconGripVertical class="size-3 text-muted-foreground" />
        <span class="sr-only">Drag to reorder</span>
    </Button>
</template>
