<template>
    <div
        v-if="active && type && message"
        class="flex items-center p-5 text-base fixed bottom-4 right-4 z-50 rounded-xl"
        :class='{
            "bg-green-50 border-green-200 text-green-800": type === "success",
            "bg-red-50 border-red-200 text-red-800": type === "error",
            "bg-yellow-50 border-yellow-200 text-yellow-800": type === "warning",
        }'
        role="alert"
    >
        <CircleCheckIcon
            v-if="type === 'success'"
            class="shrink-0 inline size-5 me-3"
        />
        <CircleXIcon
            v-else-if="type === 'error'"
            class="shrink-0 inline size-5 me-3"
        />
        <CircleAlertIcon
            v-else
            class="shrink-0 inline size-5 me-3"
        />
        <p v-text="message"></p>
    </div>
</template>

<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { router, usePage } from "@inertiajs/vue3";

    import {
        CircleCheck as CircleCheckIcon,
        CircleX as CircleXIcon,
        CircleAlert as CircleAlertIcon,
    } from 'lucide-vue-next';

    const page = usePage();

    const active = ref(false);
    const icon = ref("");
    const type = ref("");
    const message = ref<string>("");

    onMounted(() => {
        router.on('finish', () => {
            const error = (Object.values(page.props.errors)[0] || page.props.error) as string;

            if (page.props.success) {
                type.value = "success";
                message.value = page.props.success as string;
            } else if (error) {
                type.value = "error";
                message.value = error as string;
            } else if (page.props.warning) {
                type.value = "warning";
                message.value = page.props.warning as string;
            }

            setActive();
        });
    });

    function reset() {
        icon.value = "";
        type.value = "";
        message.value = "";
    }

    function setActive() {
        if (!type && !message) {
            return;
        }

        active.value = true;

        setTimeout(() => {
            active.value = false;

            reset();
        }, 4000);
    };
</script>
