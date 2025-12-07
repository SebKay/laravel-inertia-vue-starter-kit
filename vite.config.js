import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import vue from '@vitejs/plugin-vue';
import { wayfinder } from "@laravel/vite-plugin-wayfinder";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),

        tailwindcss(),

        wayfinder(),
    ],

    resolve: {
        alias: {
            '@': '/resources',
            '@fonts': '/public/fonts',
            '@img': '/public/img',
            '@js': '/resources/js',
            '@css': '/resources/css',
        },
    },
});
