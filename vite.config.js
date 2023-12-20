import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import viteReact from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/css/dashboard.css',
            'resources/css/signup.css',
            'resources/css/statistics.css',
            'resources/js/app.js',
            'resources/js/dashboard/index.js',
            'resources/js/event/index.js',
            'resources/js/signup/index.js',
            'resources/js/statistics/index.js'
        ]),
        viteReact(),
    ],
});
