import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    define: {
        __VUE_PROD_DEVTOOLS__: false,
        __VUE_PROD_DEVTOOLS_PROFILING__: false,
        __VUE_PROD_ENTERPRISE__: false,
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
    },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
