<script lang="ts">
    import { router } from "@inertiajs/vue3";
    import { defineComponent, onMounted, onUnmounted } from "vue";

    import { toast } from "@/components/ui/sonner";

    import type { FlashData } from "@js/types/inertia";

    const TOAST_DURATION = 4000;

    export default defineComponent({
        name: "FlashToasts",

        setup() {
            let removeListener: (() => void) | undefined;

            onMounted(() => {
                removeListener = router.on("flash", (event) => {
                    showFlashToast(event.detail.flash as FlashData);
                });
            });

            onUnmounted(() => {
                removeListener?.();
            });

            return () => null;
        },
    });

    function showFlashToast(flash: FlashData): void {
        if (!flash.toast) {
            return;
        }

        const { message, type } = flash.toast;

        switch (type) {
            case "success":
                toast.success(message, { duration: TOAST_DURATION });
                break;
            case "error":
                toast.error(message, { duration: TOAST_DURATION });
                break;
            case "warning":
                toast.warning(message, { duration: TOAST_DURATION });
                break;
            case "info":
                toast.info(message, { duration: TOAST_DURATION });
                break;
        }
    }
</script>
