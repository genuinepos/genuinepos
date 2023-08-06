import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/scripts/main.ts',
                'Modules/SAAS/Resources/assets/js/app.js',
                'Modules/SAAS/Resources/assets/sass/app.scss',
                'Modules/SAAS/Resources/assets/images/favicon.png',
            ],
            refresh: true,
        }),
    ],
});
