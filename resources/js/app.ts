import { createApp, defineAsyncComponent, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import type { DefineComponent } from "vue";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

const appName = import.meta.env.VITE_APP_NAME || "Template";

const AppLayout = defineAsyncComponent(() => import("@js/Layouts/App.vue"));

createInertiaApp({
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue"),
        );

        page.default.layout = page.default.layout || AppLayout;

        return page;
    },

    setup({ el, App, props, plugin }) {
        const VueApp = createApp({ render: () => h(App, props) });

        VueApp.use(plugin);

        VueApp.component("Head", Head)
            .component("Link", Link)
            .component(
                "PageTitle",
                defineAsyncComponent(
                    () => import("@js/Components/PageTitle.vue"),
                ),
            )
            .component(
                "Notice",
                defineAsyncComponent(() => import("@js/Components/Notice.vue")),
            );

        VueApp.mount(el);
    },

    defaults: {
        visitOptions: (href, options) => {
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
