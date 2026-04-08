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
        if (flash.success) {
            toast.success(flash.success, { duration: TOAST_DURATION });

            return;
        }

        if (flash.error) {
            toast.error(flash.error, { duration: TOAST_DURATION });

            return;
        }

        if (flash.warning) {
            toast.warning(flash.warning, { duration: TOAST_DURATION });

            return;
        }

        if (flash.info) {
            toast.info(flash.info, { duration: TOAST_DURATION });
        }
    }
</script>
