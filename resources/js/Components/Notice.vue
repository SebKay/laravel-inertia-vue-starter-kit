<template>
    <div
        v-if="active && type && message"
        class="inline-flex items-center gap-2 md:gap-3 p-3 md:p-5 fixed bottom-4 sm:bottom-6 xl:bottom-8 max-sm:right-4 max-md:sm:right-6 md:left-1/2 md:-translate-x-1/2 z-50 rounded-lg md:rounded-xl shadow-lg"
        :class='{
            "bg-green-50 border-green-200 text-green-800": type === "success",
            "bg-red-50 border-red-200 text-red-800": type === "error",
            "bg-yellow-50 border-yellow-200 text-yellow-800": type === "warning",
        }'
        role="alert"
    >
        <CircleCheckIcon
            v-if="type === 'success'"
            class="shrink-0 inline size-4 md:size-5"
        />
        <CircleXIcon
            v-else-if="type === 'error'"
            class="shrink-0 inline size-4 md:size-5"
        />
        <CircleAlertIcon
            v-else
            class="shrink-0 inline size-4 md:size-5"
        />
        <p
            v-text="message"
            class="max-md:text-sm"
        ></p>
    </div>
</template>

<script setup lang="ts">
    import { ref, onMounted, onUnmounted } from 'vue';
    import { router } from "@inertiajs/vue3";

    import type { FlashData } from "@js/types/inertia";

    import {
        CircleCheck as CircleCheckIcon,
        CircleX as CircleXIcon,
        CircleAlert as CircleAlertIcon,
    } from 'lucide-vue-next';

    const active = ref(false);
    const type = ref("");
    const message = ref<string>("");

    let removeListener: (() => void) | null = null;

    onMounted(() => {
        removeListener = router.on('flash', (event) => {
            const flash = event.detail.flash as FlashData;

            if (flash.success) {
                type.value = "success";
                message.value = flash.success;
            } else if (flash.error) {
                type.value = "error";
                message.value = flash.error;
            } else if (flash.warning) {
                type.value = "warning";
                message.value = flash.warning;
            }

            setActive();
        });
    });

    onUnmounted(() => {
        removeListener?.();
    });

    function reset() {
        type.value = "";
        message.value = "";
    }

    function setActive() {
        if (!type.value && !message.value) {
            return;
        }

        active.value = true;

        setTimeout(() => {
            active.value = false;

            reset();
        }, 4000);
    };
</script>
