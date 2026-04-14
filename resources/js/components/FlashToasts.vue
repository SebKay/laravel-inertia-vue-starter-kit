<script lang="ts">
    import { HttpResponseError, http } from "@inertiajs/core";
    import { router } from "@inertiajs/vue3";
    import { defineComponent, onMounted, onUnmounted } from "vue";

    import { toast } from "@/components/ui/sonner";

    import type { FlashData } from "@js/types/inertia";

    const TOAST_DURATION = 4000;

    export default defineComponent({
        name: "FlashToasts",

        setup() {
            let removeFlashListener: (() => void) | undefined;
            let removeHttpErrorListener: (() => void) | undefined;

            onMounted(() => {
                removeFlashListener = router.on("flash", (event) => {
                    showFlashToast(event.detail.flash as FlashData);
                });

                removeHttpErrorListener = http.onError((error) => {
                    showHttpErrorToast(error);
                });
            });

            onUnmounted(() => {
                removeFlashListener?.();
                removeHttpErrorListener?.();
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

    function showHttpErrorToast(error: unknown): void {
        if (
            !(error instanceof HttpResponseError) ||
            error.response.status !== 429
        ) {
            return;
        }

        toast.warning(getRateLimitMessage(error.response.headers), {
            duration: TOAST_DURATION,
        });
    }

    function getRateLimitMessage(headers: Record<string, string>): string {
        const retryAfterHeader = headers["retry-after"];
        const retryAfterSeconds = Number.parseInt(retryAfterHeader ?? "", 10);

        if (Number.isNaN(retryAfterSeconds) || retryAfterSeconds <= 0) {
            return "Please wait a moment before trying again.";
        }

        if (retryAfterSeconds >= 60) {
            const retryAfterMinutes = Math.ceil(retryAfterSeconds / 60);

            return `Please wait ${retryAfterMinutes} minute${retryAfterMinutes === 1 ? "" : "s"} before trying again.`;
        }

        return `Please wait ${retryAfterSeconds} second${retryAfterSeconds === 1 ? "" : "s"} before trying again.`;
    }
</script>
