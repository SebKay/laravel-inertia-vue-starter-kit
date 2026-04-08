import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { defineAsyncComponent } from "vue";

const appName = import.meta.env.VITE_APP_NAME || "Template";

const AppLayout = defineAsyncComponent(() => import("@js/layouts/App.vue"));
const PageTitle = defineAsyncComponent(
    () => import("@js/components/PageTitle.vue"),
);
const Notice = defineAsyncComponent(() => import("@js/components/Notice.vue"));

createInertiaApp({
    pages: "./pages",

    layout: () => AppLayout,

    withApp(app) {
        app.component("Head", Head)
            .component("Link", Link)
            .component("PageTitle", PageTitle)
            .component("Notice", Notice);
    },

    defaults: {
        visitOptions: () => {
            return {
                viewTransition: true,
            };
        },
    },

    title: (title) => (title ? `${title} | ${appName}` : appName),

    progress: {
        color: "#000",
    },
});
