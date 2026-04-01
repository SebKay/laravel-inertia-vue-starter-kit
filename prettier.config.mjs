/** @type {import('prettier').Config & import('prettier-plugin-tailwindcss').PluginOptions} */
export default {
    plugins: ["prettier-plugin-tailwindcss"],
    tailwindStylesheet: "./resources/css/app.css",
    tabWidth: 4,
};
