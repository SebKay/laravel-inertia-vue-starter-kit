import { defineAsyncComponent } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME || "Template";

const AppLayout = defineAsyncComponent(() => import("@js/Layouts/App.vue"));
const PageTitle = defineAsyncComponent(() => import("@js/Components/PageTitle.vue"));
const Notice = defineAsyncComponent(() => import("@js/Components/Notice.vue"));

createInertiaApp({
    pages: "./Pages",

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
