import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js'],
            refresh: [
                'resources/views/**/*.blade.php',
                'routes/**/*.php',
                'app/View/Components/**/*.php',
            ],
        }),
    ],
    server: {
        watch: {
            ignored: ['**/vendor/**', '**/storage/**'],
        },
    },
});
